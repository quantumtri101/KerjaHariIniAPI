<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Excel;
use PDF;
use NEWPDF;
use Image;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Helper\Controller\ExportHelper;

use App\Exports\EventReservationExport;
use App\Exports\TableReservationExport;
use App\Exports\PointTransactionExport;
use App\Exports\PayBillExport;

use App\Models\Reservation;
use App\Models\Branch;
use App\Models\Event;
use App\Models\PointTransaction;
use App\Models\Jobs;
use App\Models\Resume;

class ExportController extends BaseController{
  public function event_reservation(Request $request){
    $helper = new ExportHelper();
    
    $arr = $helper->get_event_data($request);
    return Excel::download(new EventReservationExport($arr['arr_reservation'], $arr['event']), 'event_reservation.xlsx');
  }

  public function table_reservation(Request $request){
    $helper = new ExportHelper();

    $arr = $helper->get_branch_data($request);
    return Excel::download(new TableReservationExport($arr['arr_reservation'], $arr['branch']), 'table_reservation.xlsx');
  }

  public function point_transaction(Request $request){
    $helper = new ExportHelper();

    $arr = $helper->get_point_transaction_data($request);
    return Excel::download(new PointTransactionExport($arr['arr_point_transaction'], $arr['branch']), 'point_transaction.xlsx');
  }

  public function transfer_transaction(Request $request){
    $helper = new ExportHelper();

    $arr = $helper->get_transfer_transaction_data($request);
    return Excel::download(new TransferTransactionExport($arr['arr_transfer_transaction'], $arr['branch']), 'transfer_transaction.xlsx');
  }

  public function pay_bill(Request $request){
    $helper = new ExportHelper();

    $arr = $helper->get_pay_bill_data($request);
    return Excel::download(new PayBillExport($arr['arr_pay_bill'], $arr['branch']), 'pay_bill.xlsx');
  }

  public function event_reservation_pdf(Request $request){
    $helper = new ExportHelper();
    $arr = $helper->get_event_data($request);

    $dompdf = NEWPDF::loadHTML(view('exports.event_reservation', [
      'arr_reservation' => $arr['arr_reservation'],
      'event' => $arr['event'],
    ])->render())->setPaper('a4', 'portrait');
    return $dompdf->stream('event_reservation.pdf');
  }

  public function table_reservation_pdf(Request $request){
    $helper = new ExportHelper();
    $arr = $helper->get_branch_data($request);

    $dompdf = NEWPDF::loadHTML(view('exports.table_reservation', [
      'arr_reservation' => $arr['arr_reservation'],
      'branch' => $arr['branch'],
    ])->render())->setPaper('a4', 'portrait');
    return $dompdf->stream('table_reservation.pdf');
  }

  public function point_transaction_pdf(Request $request){
    $helper = new ExportHelper();
    $arr = $helper->get_point_transaction_data($request);

    $dompdf = NEWPDF::loadHTML(view('exports.point_transaction', [
      'arr_point_transaction' => $arr['arr_point_transaction'],
      'branch' => $arr['branch'],
    ])->render())->setPaper('a4', 'portrait');
    return $dompdf->stream('point_transaction.pdf');
  }

  public function resume_pdf(Request $request){
    $resume = Resume::find($request->id);
    $arr_jobs = Jobs::where('worker_id', '=', $resume->user->id)
      ->where('status', '=', 'done')
      ->get();

    $dompdf = NEWPDF::loadHTML(view('exports.resume_print', [
      'resume' => $resume,
      'arr_jobs' => $arr_jobs,
    ])->render())->setPaper('a4', 'portrait');
    return $dompdf->stream('resume.pdf');
  }

  public function transfer_transaction_pdf(Request $request){
    $helper = new ExportHelper();
    $arr = $helper->get_transfer_transaction_data($request);

    $dompdf = NEWPDF::loadHTML(view('exports.transfer_transaction', [
      'arr_transfer_transaction' => $arr['arr_transfer_transaction'],
      'branch' => $arr['branch'],
    ])->render())->setPaper('a4', 'portrait');
    return $dompdf->stream('transfer_transaction.pdf');
  }

  public function pay_bill_pdf(Request $request){
    $helper = new ExportHelper();
    $arr = $helper->get_pay_bill_data($request);

    $dompdf = NEWPDF::loadHTML(view('exports.pay_bill', [
      'arr_pay_bill' => $arr['arr_pay_bill'],
      'branch' => $arr['branch'],
    ])->render())->setPaper('a4', 'portrait');
    return $dompdf->stream('pay_bill.pdf');
  }

  public function top_up_pdf(Request $request){
    $point_transaction = PointTransaction::find($request->id);

    // return $this->get_data_helper->return_data($request, [], 'view', 'exports.point_transaction_print', [
    //   'point_transaction' => $point_transaction,
    // ]);
    $dompdf = NEWPDF::loadHTML(view('exports.point_transaction_print', [
      'point_transaction' => $point_transaction,
    ])->render())->setPaper('a4', 'portrait');
    return $dompdf->stream('top_up.pdf');
  }
}
