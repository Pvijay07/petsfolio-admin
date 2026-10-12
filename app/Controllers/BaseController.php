<?php

namespace App\Controllers;

use App\Models\Booking;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use DateInterval;
use DatePeriod;
use DateTime;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
<<<<<<< HEAD
    protected $helpers = ['common', 'form', 'url', 'html'];
=======
    protected $helpers = [];
>>>>>>> origin/main

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    protected $bookingData = [];
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();
        // $this->setBookingCounts();
    }
    public function checkPermission($requiredPermission)
    {
        $session  = session();
        $userRole = $session->get('user_role');

        if (!hasPermission($requiredPermission, $userRole)) {
            return redirect()->to('/unauthorized')->with('error', 'Access denied.');
        }
    }
    private function setBookingCounts()
    {
        $bookingModel   = new Booking();
        $haltedBookings = $bookingModel->getHaltedWalkingBookings();
        $filtered = [];
        foreach ($haltedBookings as $booking) {
            $number  = trim(strtolower($booking->user_phone ?? ''));
            $pet     = trim(strtolower($booking->pet_name ?? ''));
            $service = trim(strtolower($booking->service_name ?? ''));

            $baseKey  = "{$number}_{$pet}";
            $comboKey = "{$baseKey}_{$service}";

            // Allow unique combination (number, pet, service_type)
            if (!isset($seen[$comboKey])) {
                $seen[$comboKey] = true;
                $filtered[]      = $booking;
            }
        }
        $supportCount = [];
        $supports = $bookingModel->getSupportchat();
        foreach ($supports as $support) {
            if ($support->status != 'completed') {

                $supportCount[]      = $support;
            }
        }
        $clsupportCount = [];
        $clsupports = $bookingModel->getSupport();
        foreach ($clsupports as $clsupport) {
            if ($clsupport->status == 'open') {

                $clsupportCount[]      = $clsupport;
            }
        }
        $totalHaltedBookings = $filtered;
        $this->bookingData   = [
            'Confirmedbookings' => count($bookingModel->getBookings('Confirmed')),
            'Haltedbookings'    => count($totalHaltedBookings),
            'Cancelledbookings' => count($bookingModel->getCancelledBookings('cancelled')),
            'Deletedbookings'   => count($bookingModel->getDeletedBookings('deleted')),
            'Completedbookings' => count($bookingModel->getCompletedBookings()),
            'bookingRequests'   => count($this->getBookingRequests()),
            'missedwalks'       => count($bookingModel->getUntrackedWalks()),
            'clsupports'        => count($clsupportCount),
            'spsupports'        => count($supportCount)
        ];
    }
    public function getBookingRequests()
    {
        $bookingModel = new Booking();

        // Get grooming bookings (assuming 'New' status)
        $groomingBookings = $bookingModel->getGroomingBookings('New');
        $walkingBookings  = $bookingModel->getWalkingBookings('New');
        $trainingBookings = $bookingModel->getTrainingBookings('New');
<<<<<<< HEAD
        $sittingBookings  = $bookingModel->getSittingBookings('New');

        $bookings = array_merge($groomingBookings ?? [], $walkingBookings ?? [], $trainingBookings ?? [], $sittingBookings ?? []);
=======

        $bookings = array_merge($groomingBookings, $walkingBookings, $trainingBookings);
>>>>>>> origin/main
        foreach ($bookings as $booking) {


            $today     = date('Y-m-d');
            $startDate = new DateTime($today);

            // Check if 'service_start_date' exists and is valid
            if (!empty($booking->service_start_date)) {
                $endDate   = new DateTime($booking->service_start_date);
                $interval  = $startDate->diff($endDate);
                $totalDays = $interval->days + 1;

                // Check if 'service_days' exists before accessing it
                if (property_exists($booking, 'service_days') && $booking->service_days == 'weekdays') {
                    $period    = new DatePeriod($startDate, new DateInterval('P1D'), $endDate);
                    $totalDays = 0;

                    foreach ($period as $date) {
                        // Check if the day is not Sunday (6 is Sunday in PHP's w format)
                        if ($date->format('w') != 0) {
                            $totalDays++;
                        }
                    }
                } else {
                    $totalDays = $startDate->diff($endDate)->days;
                }
            } else {
                // If no end date, default to 1 day for grooming bookings
                $totalDays = 1;
            }

            $booking->total_days = $totalDays;
        }

        return $bookings;
    }
    protected function loadView($view, $data = [])
    {
        // Merge booking data into the passed view data
        $data = array_merge($this->bookingData, $data);
        return view($view, $data);
    }
}
