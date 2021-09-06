<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Managers\V1APIManager;
use App\Models\ApiHistory;
use App\Models\Packet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * Class CommonAPIController
 * @package App\Http\Controllers\API
 */
class V1APIController extends AppBaseController
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('main_pages.api');
    }

    /**
     * @param $result
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResponse($result, $code = 200)
    {
        return response()->json([
            'data' => $result,
            'error' => null
        ], $code);
    }

    /**
     * @param $error
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendError($error, $code = 404)
    {
        return response()->json([
            'data' => null,
            'error' => $error
        ], $code);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException|ValidationException|\Exception
     */
    public function handle(Request $request)
    {
        try {
            /** @var ApiHistory $history */
            $history = ApiHistory::create(['user_id' => Auth::id()]);

            $request->validate(['action' => 'required|in:' . implode(',', config('enumerations.api_actions'))]);

            $managerAPI = new V1APIManager();
            $history->setAction($request->get('action'));

            switch ($request->get('action')) {
                case 'balance':
                    $data = $managerAPI->getActionBalanceResponse();
                    $history->setResponse(json_encode($data));
                    break;
                case 'services':
                    $data = $managerAPI->getActionServicesResponse();
                    break;
                case 'add':
                    $history->setRequest(json_encode($request->only(['service', 'link', 'quantity'])));
                    $request->validate([
                        'service' => 'required|exists:packets,id',
                        'link' => 'required|string|min:3',
                        'quantity' => 'required|integer'
                    ]);

                    /** @var Packet $packet */
                    $packet = Packet::find($request->get('service'));
                    $this->validate($request, [
                        'quantity' => [function ($attribute, $value, $fail) use ($packet) {
                            if ($value < $packet->min) {
                                $fail('Min count by current service: ' . $packet->min);
                            }
                        }, function ($attribute, $value, $fail) use ($packet) {
                            if ($value > $packet->max) {
                                $fail('Max count by current service: ' . $packet->max);
                            }
                        }]
                    ]);
                    $data = $managerAPI->createOrder($packet, $request->get('link'), $request->get('quantity'));
                    $history->setResponse(json_encode($data));
                    break;
                case 'status':
                    $request->validate([
                        'order' => 'required_without:orders|nullable',
                        'orders' => 'required_without:order|nullable'
                    ]);
                    if (empty($request->get('order'))) {
                        $history->setRequest(json_encode($request->only('orders')));
                        $data = $managerAPI->getActionStatusOrdersResponse($request->get('orders'));
                    } else {
                        $history->setRequest(json_encode($request->only('order')));
                        $data = $managerAPI->getActionStatusOrderResponse($request->get('order'));
                    }
                    $history->setResponse(json_encode($data));
                    break;
                default:
                    throw ValidationException::withMessages(['action' => 'The action field is required.']);
            }
            return $this->sendResponse($data);
        } catch (ValidationException $e) {
            if (!empty($history)) {
                $history->setResponse(json_encode(['error' => $e->validator->errors()->getMessages()]));
            }
            return $this->sendError($e->validator->errors()->getMessages(), 422);
        } catch (\Exception $e) {
            if (!empty($history)) {
                $history->setResponse(json_encode(['error' => $e->getMessage()]));
            }
            return $this->sendError($e->getMessage(), 500);
        }
    }
}