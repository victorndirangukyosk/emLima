<?php


require_once DIR_ROOT . '/vendor/autoload.php';

use mikehaertl\wkhtmlto\Pdf; 

class ControllerCommonSchedulerPayment extends Controller {

    private $error = [];    

    public function UpdatePaybillPayments() {
        $log = new Log('error.log');
        try {
            $log->write('UpdatePaybillPayments -' . date('Y-m-d H:i:s'));
            $this->load->model('scheduler/dbupdates');
            //Get unallocated Funds Customers
            $result_custs = $this->model_scheduler_dbupdates->GetUnallocatedFundCustomers();
            // echo "<pre>";print_r($result_custs);die; 
            foreach ($result_custs as $cust) {
                //get oending orders in FIFO (deivery date ASC)
                $result_orders=$this->model_scheduler_dbupdates->GetCustomerPendingOrders($cust['customer_id']);
                // echo "<pre>";print_r($result_orders);die; 
                
                $order_total_pending_Value = 0;
                foreach ($result_orders as $ord) {
                    // if($ord['paid']=="N")
                    // {
                    //   $order_total_pending_Value += $ord['total'];
                    // }
                    // else if($ord['paid']=="P")
                    // {
                    //     if($ord['total']==$ord['amount_partialy_paid'])
                    //     {$order_total_pending_Value += 0;}
                    //     else
                    //     {$order_total_pending_Value += ($ord['total']-$ord['amount_partialy_paid'] );}

                    // }
                    //funds fetching should be in inner for loop, because funds balance will be updated based on order totals
                     $result_funds = $this->model_scheduler_dbupdates->GetUnallocatedFunds($cust['customer_id']);
                    //  echo "<pre>";print_r($result_funds);die; 

                    $closed=0;
                    $order_paid=0;
                    $ord['pending_total']=$ord['total']-$ord['amount_partialy_paid'];
                    foreach ($result_funds as $fund) {
                     if( ($fund['available_balance']==$ord['pending_total']) && $order_paid==0)
                     {//update order to paid and deduct from funds_totls
                        //and finally map the fund id as closed/totaly used,reset pending_amount
                        $this->model_scheduler_dbupdates->confirmPaymentReceived($ord['order_id'], $fund['transaction_id'], $fund['amount'],0, 'Pay bill No',$ord['pending_total'],$fund['available_balance']);
                        $order_paid=1;
                        $closed=1;     
                        $available_balance=0;
                        $amount_used   =  $fund['available_balance']    ;                    
                        $this->model_scheduler_dbupdates->updateFundAndTotals($fund['customer_fund_id'], $closed,$available_balance,$amount_used,$cust['customer_id']);
                            

                     }
                     else if(($fund['available_balance']>$ord['pending_total']) && $order_paid==0 )
                     {// deduct from funds_totals
                        //and finally map the fund id as closed/totaly used,
                        $this->model_scheduler_dbupdates->confirmPaymentReceived($ord['order_id'], $fund['transaction_id'], $fund['amount'],0, 'Pay bill No',$ord['pending_total'],$ord['pending_total']);

                        $order_paid=1;$closed=0; 
                        $available_balance=$fund['available_balance']-$ord['pending_total'];
                        $amount_used   =  $fund['amount_used']+$ord['pending_total']   ;                    
                        $this->model_scheduler_dbupdates->updateFundAndTotals($fund['customer_fund_id'], $closed,$available_balance,$amount_used,$cust['customer_id']);
                        //extra amount adding to wallet is in discussion
                       //even after discussion, write seperate job for crediting amount, as forloop is using
                        //and need to run that job , once the payment job is completed

                     }
                     else if($fund['available_balance']<$ord['pending_total'] && $order_paid==0 )
                     {//update order to partialy paid and
                       
                        $amount_partialy_paid=$fund['available_balance']+$ord['amount_partialy_paid'];
                       
                        $this->model_scheduler_dbupdates->confirmPartialPaymentReceived($ord['order_id'], $fund['transaction_id'], $fund['amount'],$amount_partialy_paid, 'Pay bill No',$ord['pending_total'],$fund['available_balance']);

                        //update available balance and amount_used
                        $order_paid=0;
                        $closed=1;
                        // $available_balance=$fund['available_balance']-$fund['available_balance'];
                        $available_balance=0;
                        $amount_used   =  $fund['amount_used']+$fund['available_balance']   ;                    
                        $this->model_scheduler_dbupdates->updateFundAndTotals($fund['customer_fund_id'], $closed,$available_balance,$amount_used,$cust['customer_id']);
                        
                        // finally get the updated orders , as in partial payment again , we need to check the updated values
                       
                        $ord['amount_partialy_paid']=$amount_partialy_paid;
                        $ord['pending_total']= $ord['total']-$ord['amount_partialy_paid'];


                     }
                    }              


                }                              
                 
            }
            $log->write('UpdatePaybillPayments - Done ');
            echo "updated successfully";
        } catch (exception $ex) {
            $log->write('UpdatePaybillPayments -' . $ex);
        }
    }
           
}
