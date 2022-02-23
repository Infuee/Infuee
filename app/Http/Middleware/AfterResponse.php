<?php
namespace App\Http\Middleware;
use Closure;
use App\Models\Transactions;
use Illuminate\Support\Facades\Auth;
use App;
use Illuminate\Support\Facades\Storage;
use App\http\Controllers\CartController;
class AfterResponse
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }


    public function terminate($request, $response)
    {

        $responseContent = json_decode($response->getContent());
        $order_id = isset($responseContent->order_id) ? $responseContent->order_id : $request->session()->get('order_id');
        $notification = new CartController();
        $notification->sendPdf($order_id);
        /*$Transactions = Transactions::with('user','order','order.order_items','order.order_items.userPlan','order.order_items.userPlan.allPlan','order.order_items.userPlan.allPlan.allCategory')->where('order_id',$order_id)->get();
        foreach($Transactions AS $Transaction){
            $trans = Transactions::where('id',$Transaction->id)->first();
            $trans->invoice = 1;
            $trans->save();
            $html = view('admin.transactions.pdf',compact('Transaction'))->render();
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadHTML($html);
            $path = 'pdf/order/'.$Transaction->order_id; 

            $email = 'nikhil@yopmail.com';
            \Mail::send('email.test', ['responseContent' => $responseContent,'request'=>$Transaction->order_id], function($message ) use ($email){
                        $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                        ->subject('Terminate Recieved new')
                        ->to($email);
                    });
            $this->sendMail($response);

            if(!Storage::disk('public_uploads')->put($path.'/invoice.pdf', $pdf->output())) {
                return false;
            }
        }*/

        

        //Send Notification
        // $this->SendMblNoti($response);
    }
}
