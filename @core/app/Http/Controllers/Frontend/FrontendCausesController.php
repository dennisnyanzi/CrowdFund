<?php

namespace App\Http\Controllers\Frontend;

use App\Admin;
use App\Cause;
use App\CauseCategory;
use App\CauseLogs;
use App\CauseUpdate;
use App\Comment;
use App\FlagReport;
use App\Gift;
use App\Helpers\DonationHelpers;
use App\Helpers\FlashMsg;
use App\Mail\BasicMail;
use App\Http\Controllers\Controller;
use App\Reward;
use App\User;
use App\UserFollow;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;


class FrontendCausesController extends Controller
{

    private const BASE_PATH = 'frontend.donations.';

    public function donations()
    {
        
        $all_donations = Cause::where(['status' => 'publish'])
        ->whereDate("deadline", '>', \Carbon\Carbon::now())
        ->orWhereNull("deadline")
        ->orderBy('id', 'desc')
        ->paginate(get_static_option('donor_page_post_items'));
        
        return view(self::BASE_PATH . 'donation')->with([
            'all_donations' => $all_donations
        ]);
    }

    public function donations_single($slug)
    {
        $donation = Cause::where('slug', $slug)->first();
        
        if (empty($donation)) {
            return redirect_404_page();
        }
        if ( in_array($donation->status,['pending','draft']) && $donation->created_by === 'user'){
            return redirect_404_page();
        }
        
        $all_donations = CauseLogs::where(['status' => 'complete', 'cause_id' => $donation->id])->orderBy('id', 'desc')->paginate(5);
        $causeUpCount = CauseUpdate::where('cause_id', $donation->id)->get();
        $causeCommentCount = Comment::where('cause_id', $donation->id)->get();
        $all_donors = CauseLogs::where(['cause_id' => $donation->id])->take(3)->get();
        $all_related_cause = Cause::Where(['status' => 'publish' , 'categories_id' => $donation->categories_id])->orderBy('id', 'desc')->take(6)->get();

        $user = '';
        if($donation->created_by === 'user'):
             $user = $donation->user;
             $user_type = "user";
        else:
             $user = $donation->admin;
             $user_type = "admin";
        endif;

        $follow = '';
        if (!is_null($user)){
            $follow = UserFollow::where(['campaign_owner_id'=>$user->id, 'user_type'=> $user_type])->first();
        }


        $follow_user = $follow ;
        $minimum_goal_amount = Reward::where('status','publish')
            ->whereDate('reward_expire_date', '>', Carbon::now()) //not show expired reward
            ->orderBy('reward_goal_from','asc')->get()->min('reward_goal_from');

        return view(self::BASE_PATH . 'donation-single')->with([
            'all_donations' => $all_donations,
            'donation' => $donation,
            'causeUpCount' => $causeUpCount,
            'causeCommentCount' => $causeCommentCount,
            'all_donors' => $all_donors,
            'all_related_cause' => $all_related_cause,
            'follow_user' => $follow_user,
            'minimum_goal_amount' => $minimum_goal_amount,
            'type' => request()->get('type') ?? null,
        ]);
    }

    public function donations_in_separate_page($id)
    {
        $donation = Cause::where('id', $id)->first();
        if (empty($donation) || !empty($donation->deadline) && $donation->deadline <= date('Y-m-d')) {
            return view('errors.403')->with(['message' => __('Donation Expired')]);
        }

        if(!empty(get_static_option('donation_login_user_donate_show_hide')) && !auth()->guard('web')->check()){
            abort(404);
        }
        $all_donations = CauseLogs::where(['status' => 'complete', 'cause_id' => $donation->id])->orderBy('id', 'desc')->paginate(5);
        $causeUpCount = CauseUpdate::where('cause_id', $donation->id)->get();
        $causeCommentCount = Comment::where('cause_id', $donation->id)->get();
        $all_donors = CauseLogs::where('cause_id', $donation->id)->take(3)->get();

        return view(self::BASE_PATH . 'donation-payment-separate-page')->with([
            'all_donations' => $all_donations,
            'donation' => $donation,
            'causeUpCount' => $causeUpCount,
            'causeCommentCount' => $causeCommentCount,
            'all_donors' => $all_donors,
        ]);
    }

    public function donation_payment_success($id)
    {
        $extract_id = substr($id, 6);
        $extract_id = substr($extract_id, 0, -6);
        $donation_logs = CauseLogs::find($extract_id);
        $donation = Cause::find($donation_logs->cause_id);
        return view(self::BASE_PATH . 'donation-success')->with(['donation_logs' => $donation_logs, 'donation' => $donation]);
    }

    public function donation_payment_cancel()
    {
        return view(self::BASE_PATH . 'donation-cancel');
    }


    public function generate_donation_invoice(Request $request)
    {
        $donation_details = CauseLogs::findOrFail($request->id);
        $pdf = PDF::loadView('invoice.donation', ['donation_details' => $donation_details]);
        return $pdf->download('donation-invoice.pdf');
    }

    public function donation_by_category($id)
    {
        $all_donations = Cause::where(['categories_id' => $id,'status' => 'publish'])->whereDate("deadline", '>', \Carbon\Carbon::now())->get();
        return view(self::BASE_PATH . 'donation-category', compact('all_donations'));
    }

    public function cause_comment_store(Request $request)
    {
        $this->validate($request, [
            'comment_content' => 'required'
        ]);

        $content = Comment::create([
            'cause_id' => $request->cause_id,
            'user_id' => $request->user_id,
            'commented_by' => $request->commented_by,
            'comment_content' => purify_html($request->comment_content),
        ]);

      try{
            Mail::to(get_static_option('site_global_email'))->send(new BasicMail([
            'subject' => __('You have a comment from') . ' ' . get_static_option('site_title'),
            'message' => __('you have a new comment submitted by') . ' ' . Auth::user()->name . ' ' . __('Email') . ' ' . Auth::user()->email . ' .' . __('check admin panel for more info'),
        ]));
      }catch(\Exception $e){
          
      }
        return response()->json([
            'msg' => __('Your comment sent succefully'),
            'type' => 'success',
            'status' => 'ok',
            'content' => $content,
        ]);
    }

    public function cause_all_comment()
    {
        $all_comment = Comment::with(['cause', 'user'])->get();
        return response()->json($all_comment);
    }


    public function load_donor_data(Request $request)
    {
          $skip = $request->page ?? 0;
          $all_donors = CauseLogs::where(['cause_id' => $request->id,'status' => 'complete'])
              ->orderBy('id', 'DESC')
              ->skip($skip)
              ->take(5)
              ->get();
          $data = [];
          foreach ($all_donors as $donor){
              $data[] = [
                  'id' => $donor->id,
                  'name' => $donor->anonymous == 1 ? __('Anonymous') : $donor->name,
                  'amount' => amount_with_currency_symbol($donor->amount),
                  'date' => $donor->created_at->format('D d, M Y h:i:s'),
              ];
          }

          return response()->json($data);
    }

    public function load_donation_update_data(Request $request)
    {
          $skip = $request->page ?? 0;
          $all_donors = CauseUpdate::where('cause_id', $request->id)
              ->orderBy('id', 'DESC')
              ->skip($skip)
              ->take(5)
              ->get();
          $data = [];


          foreach ($all_donors as $donor){
              $image = get_attachment_image_by_id($donor->image);
              $img_url = $image['img_url'] ?? '';
              $data[] = [
                  'id' => $donor->id,
                  'image' => $donor->image,
                  'img_markup' => render_image_markup_by_attachment_id($donor->image,'','thumb'),
                  'img_url' => $img_url,
                  'title' => purify_html($donor->title),
                  'description' => purify_html($donor->description),
                  'date' => $donor->created_at->diffForHumans(),
                  'created_by' => optional(optional($donor->cause)->admin)->name ?? optional(optional($donor->cause)->user)->name ,
              ];
          }

          return response()->json($data);
    }


    public function load_cause_comment_data(Request $request)
    {
          $skip = $request->page ?? 0;
          $all_comments = Comment::where('cause_id', $request->id)

              ->skip($skip)
              ->take(5)
               ->orderBy('id', 'DESC')
               ->get();

          $data = [];
          foreach ($all_comments as $comment){
              $data[] = [
                  'id' => $comment->id,
                  'commented_by' => $comment->commented_by,
                  'comment_content' => purify_html($comment->comment_content),
                  'date' => $comment->created_at->format('D, d M Y'),
              ];
          }

          return response()->json($data);
    }

    public function get_donation_charges_by_ajax(Request $request){
        
        $donation_amount = $request->amount;
        
        return response()->json([
            'donation_amount' => amount_with_currency_symbol($donation_amount),
            'tip_amount' => DonationHelpers::get_donation_charge($donation_amount,false,$request->admin_tip),
            'tip' => DonationHelpers::get_donation_charge($donation_amount,true,$request->admin_tip),
            'total' => DonationHelpers::get_donation_total($donation_amount,true,$request->admin_tip)
        ]);
        
    }

    public function flag_report_store(Request $request){
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'email' => 'required|string|max:191',
            'subject' => 'required|string|max:191',
            'description' => 'required|string'
        ]);

        FlagReport::create([
            'cause_id'=> $request->cause_id,
            'name'=> purify_html($request->name),
            'email'=> purify_html($request->email),
            'subject'=> purify_html($request->subject),
            'description'=> purify_html($request->description),
        ]);

        return redirect()->back()->with(FlashMsg::item_new(__('We will review your claim shortly..!')));
    }

    public function user_created_donations($user,$id)
    {
        $id_type = $user === 'user' ? 'user_id' : 'admin_id';
        $user_donations = Cause::where(['created_by' => $user,$id_type =>$id,'status'=>'publish'])->paginate(9);
        $user_info = $user === 'user' ? User::find($id) : Admin::find($id);
        if (empty($user_info)){
            abort(404);
        }
        return view(self::BASE_PATH.'user-donations',compact('user_donations','user_info'));
    }

    public function donation_search_page(Request $request)
    {

        $request->validate([
            'search' => 'required'
        ]);

        if($request->search == ''){
            return redirect()->route('homepage')->with(['type'=>'danger', 'msg'=> __('Search Field is required')]);
        }


        $all_donations = Cause::Where('title', 'LIKE', '%' . $request->search . '%')->whereDate("deadline", '>', \Carbon\Carbon::now())
            ->orderBy('id', 'desc')->paginate(3);

        return view('frontend.donations.donation-search')->with([
            'all_donations' => $all_donations,
            'search_term' => $request->search,

        ]);
    }


    public function gift_checkout($id,$d_id)
    {
        $gift = Gift::findOrFail($id);
        $gift_name = Gift::findOrFail($id)->title;
        $donation = Cause::findOrFail($d_id);

        if(empty($gift) && empty($donation) ){
            abort(404);
        }

        return view('frontend.donations.gift-checkout',compact('gift','gift_name','donation'));
    }

    public function donations_recuring_separate($token)
    {
        $donation = CauseLogs::where('recuring_token',$token)->first();

        if(empty($donation)){
            abort(404);
        }

        return view(self::BASE_PATH . 'donation-recurring')->with([
            'donation' => $donation,
        ]);
    }



}
