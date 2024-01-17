<?php

namespace Modules\SupportTicket\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\SupportTicket\Entities\Ticket;
use Modules\SupportTicket\Entities\TicketCategory;
use App\Models\User;
use App\Models\WorkSpace;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function __construct()
    {
        if (module_is_active('GoogleAuthentication')) {
            $this->middleware('2fa');
        }
    }
    public function index()
    {
        if (Auth::check()) {
            if (Auth::user()->isAbleTo('supportticket dashboard manage')) {
                $categories   = TicketCategory::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->count();
                $open_ticket  = Ticket::whereIn('status', ['On Hold', 'In Progress'])->where('workspace_id', getActiveWorkSpace())->count();
                $close_ticket = Ticket::where('status', '=', 'Closed')->where('workspace_id', getActiveWorkSpace())->count();
                $workspace       = WorkSpace::where('id', getActiveWorkSpace())->first();

                $categoriesChart = Ticket::select(
                    [
                        'ticket_categories.name',
                        'ticket_categories.color',
                        \DB::raw('count(*) as total'),
                    ]
                )->join('ticket_categories', 'ticket_categories.id', '=', 'tickets.category')->where('tickets.workspace_id', getActiveWorkSpace())->groupBy('ticket_categories.id')->get();
                $chartData = [];
                $chartData['color'] = [];
                $chartData['name']  = [];
                $chartData['value'] = [];

                if (count($categoriesChart) > 0) {
                    foreach ($categoriesChart as $category) {
                        $chartData['name'][]  = $category->name;
                        $chartData['value'][] = $category->total;
                        $chartData['color'][] = $category->color;
                    }
                } else {
                    $chartData['color'] = ['#5ce600'];
                    $chartData['name'] = ['Category'];
                    $chartData['value'] = [100];
                }

                $monthData = [];
                $barChart  = Ticket::select(
                    [
                        \DB::raw('MONTH(created_at) as month'),
                        \DB::raw('YEAR(created_at) as year'),
                        \DB::raw('count(*) as total'),
                    ]
                )->where('created_at', '>', \DB::raw('DATE_SUB(NOW(),INTERVAL 1 YEAR)'))->where('workspace_id', getActiveWorkSpace())->groupBy(
                    [
                        \DB::raw('MONTH(created_at)'),
                        \DB::raw('YEAR(created_at)'),
                    ]
                )->get();

                $start = \Carbon\Carbon::now()->startOfYear();

                for ($i = 0; $i <= 11; $i++) {

                    $monthData[$start->format('M')] = 0;
                    foreach ($barChart as $chart) {
                        if (intval($chart->month) == intval($start->format('m'))) {
                            $monthData[$start->format('M')] = $chart->total;
                        }
                    }
                    $start->addMonth();
                }
                $chartDatas                  = $this->getOrderChart(['duration' => 'week']);

                return view('supportticket::dashboard.index', compact('categories', 'open_ticket', 'close_ticket', 'chartData', 'workspace', 'monthData', 'chartDatas'));
                return view('supportticket::index');
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        } else {
            return redirect()->route('login');
        }
    }


    public function getOrderChart($arrParam)
    {

        $arrDuration = [];
        if ($arrParam['duration']) {
            if ($arrParam['duration'] == 'week') {
                $previous_week = strtotime("-1 week +1 day");
                for ($i = 0; $i < 7; $i++) {
                    $arrDuration[date('Y-m-d', $previous_week)] = date('d-m', $previous_week);
                    $previous_week                              = strtotime(date('Y-m-d', $previous_week) . " +1 day");
                }
            }
        }


        $arrTask          = [];
        $arrTask['label'] = [];
        $arrTask['data']  = [];

        return $arrTask;
    }


    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('supportticket::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('supportticket::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('supportticket::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
