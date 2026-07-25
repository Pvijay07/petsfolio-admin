<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Models\LoyaltyWalletModel;
use App\Models\LoyaltyRewardModel;
use App\Models\LoyaltyUserMilestoneModel;
use App\Models\LoyaltyTransactionModel;
use App\Models\LoyaltyMilestoneModel;
use App\Models\RewardOrderModel;
use App\Models\LoyaltySettingModel;

class LoyaltyAdminController extends BaseController
{
  protected $walletModel;
  protected $rewardModel;
  protected $userMilestoneModel;
  protected $transactionModel;
  protected $milestoneModel;
  protected $orderModel;

  public function __construct()
  {
    $this->walletModel        = new LoyaltyWalletModel();
    $this->rewardModel        = new LoyaltyRewardModel();
    $this->userMilestoneModel = new LoyaltyUserMilestoneModel();
    $this->transactionModel   = new LoyaltyTransactionModel();
    $this->milestoneModel     = new LoyaltyMilestoneModel();
    $this->orderModel         = new RewardOrderModel();
    $this->settingModel       = new LoyaltySettingModel();
  }

  /**
   * 🏰 Admin Dashboard View
   */
  public function index()
  {
    // 1. Users with points (from wallets)
    $usersWithPoints = $this->walletModel
      ->select ( 'loyalty_wallet.*, users.name, users.phone, users.email' )
      ->join ( 'users', 'users.id = loyalty_wallet.user_id' )
      ->findAll ();

    // Counts for stats cards
    $pendingCount = $this->orderModel->where ( 'order_status !=', 'delivered' )->countAllResults ();
    $deliveredCount = $this->orderModel->where ( 'order_status', 'delivered' )->countAllResults ();

    return view ( 'admin/loyalty/loyalty_admin_dashboard', [
      'users'     => $usersWithPoints,
      'pending'   => $pendingCount,
      'delivered' => $deliveredCount
    ] );
  }

  /**
   * 🎁 Reward Orders Page
   */
  public function rewardOrders()
  {
    $pendingOrders = $this->orderModel
      ->select ( 'reward_orders.*, users.name, users.phone, loyalty_rewards.reward_name as item_name' )
      ->join ( 'users', 'users.id = reward_orders.user_id' )
      ->join ( 'loyalty_rewards', 'loyalty_rewards.id = reward_orders.reward_id', 'left' )
      ->where ( 'order_status !=', 'delivered' )
      ->findAll ();

    return view ( 'admin/loyalty/reward_orders', [
      'pending' => $pendingOrders
    ] );
  }

  /**
   * 🚚 Delivered Orders Page
   */
  public function deliveredOrders()
  {
    $deliveredOrders = $this->orderModel
      ->select ( 'reward_orders.*, users.name, loyalty_rewards.reward_name as item_name' )
      ->join ( 'users', 'users.id = reward_orders.user_id' )
      ->join ( 'loyalty_rewards', 'loyalty_rewards.id = reward_orders.reward_id', 'left' )
      ->where ( 'order_status', 'delivered' )
      ->findAll ();

    return view ( 'admin/loyalty/delivered_orders', [
      'delivered' => $deliveredOrders
    ] );
  }

  /**
   * 🚚 Update Delivery Status
   */
  public function updateStatus()
  {
    $id          = $this->request->getPost ( 'id' );
    $status      = $this->request->getPost ( 'status' );
    $trackingUrl = $this->request->getPost ( 'tracking_url' );

    if ( $id && $status ) {
      $updateData = ['order_status' => $status];
      if ( $trackingUrl ) {
        $updateData['tracking_url'] = $trackingUrl;
      }
      $this->orderModel->update ( $id, $updateData );
      return redirect ()->to ( base_url ( 'loyalty-admin' ) )->with ( 'success', 'Order status updated successfully' );
    }

    return redirect ()->to ( base_url ( 'loyalty-admin' ) )->with ( 'errors', 'Failed to update order status' );
  }

  /**
   * 🏆 Programs Management (Milestones & Reward Pool)
   */
  public function programs()
  {
    $milestones = $this->milestoneModel->findAll ();
    $rewards    = $this->rewardModel
      ->select ( 'loyalty_rewards.*, loyalty_milestones.tier_name,loyalty_milestones.points_required' )
      ->join ( 'loyalty_milestones', 'loyalty_milestones.id = loyalty_rewards.milestone_id' )
      ->findAll ();

    return view ( 'admin/loyalty/loyalty_programs', [
      'milestones' => $milestones,
      'rewards'    => $rewards
    ] );
  }

  /**
   * 📜 Global Transaction History
   */
  public function transactions()
  {
    $type  = $this->request->getGet ( 'type' );
    $query = $this->transactionModel
      ->select ( 'loyalty_transactions.*, users.name' )
      ->join ( 'users', 'users.id = loyalty_transactions.user_id' );

    if ( $type === 'earn' ) {
      $query->where ( 'loyalty_transactions.type', 'earn' );
    }
    elseif ( $type === 'redeem' ) {
      $query->where ( 'loyalty_transactions.type', 'redeem' );
    }

    $transactions = $query->orderBy ( 'created_at', 'DESC' )->findAll ();

    return view ( 'admin/loyalty/loyalty_transactions', [
      'transactions' => $transactions,
      'currentType'  => $type
    ] );
  }

  public function toggleReward( $id )
  {
    $reward = $this->rewardModel->find ( $id );
    if ( $reward ) {
      $this->rewardModel->update ( $id, ['is_active' => (int) !$reward['is_active']] );
      return redirect ()->to ( base_url ( 'loyalty-programs' ) )->with ( 'success', 'Reward status updated' );
    }
    return redirect ()->to ( base_url ( 'loyalty-programs' ) )->with ( 'errors', 'Reward not found' );
  }

  /**
   * ⚙️ Loyalty Settings
   */
  public function settings()
  {
    $settings = $this->settingModel->findAll ();
    $config   = [];
    foreach ( $settings as $s ) {
      $config[$s['key']] = $s['value'];
    }

    return view ( 'admin/loyalty/loyalty_settings', ['config' => $config] );
  }

  /**
   * 💾 Save Loyalty Settings
   */
  public function saveSettings()
  {
    $data = $this->request->getPost ();

    foreach ( $data as $key => $value ) {
      $this->settingModel->where ( 'key', $key )->set ( ['value' => $value] )->update ();
    }

    return redirect ()->to ( base_url ( 'loyalty-settings' ) )->with ( 'success', 'Settings updated successfully' );
  }

  /**
   * 💾 Milestone CRUD
   */
  public function saveMilestone()
  {
    $id   = $this->request->getPost ( 'id' );
    $data = [
      'tier_name'       => $this->request->getPost ( 'tier_name' ),
      'points_required' => $this->request->getPost ( 'points_required' ),
      'spin_enabled'    => $this->request->getPost ( 'spin_enabled' ),
      'spend_amount'    => $this->request->getPost ( 'spend_amount' ) ?? 0,
    ];

    if ( $id ) {
      $this->milestoneModel->update ( $id, $data );
    }
    else {
      $this->milestoneModel->insert ( $data );
    }

    return redirect ()->to ( base_url ( 'loyalty-programs' ) )->with ( 'success', 'Milestone saved' );
  }

  public function deleteMilestone( $id )
  {
    $this->milestoneModel->delete ( $id );
    return redirect ()->to ( base_url ( 'loyalty-programs' ) )->with ( 'success', 'Milestone deleted' );
  }

  /**
   * 💾 Reward CRUD
   */
  public function saveReward()
  {
    $id   = $this->request->getPost ( 'id' );
    $data = [
      'milestone_id' => $this->request->getPost ( 'milestone_id' ),
      'reward_name'  => $this->request->getPost ( 'reward_name' ),
      'reward_type'  => $this->request->getPost ( 'reward_type' ),
      'reward_value' => $this->request->getPost ( 'reward_value' ),
      // 'probability'  => $this->request->getPost ( 'probability' ),
      'metadata'     => json_encode ( [] ),
      'is_active'    => $this->request->getPost ( 'is_active' ) ?? 0,
    ];

    // Handle Image Upload
    $img = $this->request->getFile ( 'reward_image' );
    if ( $img && $img->isValid () && !$img->hasMoved () ) {
      // Create directory if not exists
      if ( !is_dir ( FCPATH . 'uploads/rewards' ) ) {
        mkdir ( FCPATH . 'uploads/rewards', 0777, true );
      }

      // Delete old image if exists
      $oldImage = $this->request->getPost ( 'existing_image' );
      if ( $oldImage && file_exists ( FCPATH . $oldImage ) ) {
        @unlink ( FCPATH . $oldImage );
      }

      $newName = $img->getRandomName ();
      $img->move ( FCPATH . 'public/uploads/rewards', $newName );
      $data['image'] = 'public/uploads/rewards/' . $newName;
    }
    else {
      $data['image'] = $this->request->getPost ( 'existing_image' );
    }

    if ( $id ) {
      $this->rewardModel->update ( $id, $data );
    }
    else {
      $this->rewardModel->insert ( $data );
    }

    return redirect ()->to ( base_url ( 'loyalty-programs' ) )->with ( 'success', 'Reward saved' );
  }

  public function deleteReward( $id )
  {
    $reward = $this->rewardModel->find ( $id );
    if ( $reward && !empty ( $reward['reward_image'] ) ) {
      if ( file_exists ( FCPATH . $reward['reward_image'] ) ) {
        @unlink ( FCPATH . $reward['reward_image'] );
      }
    }
    $this->rewardModel->delete ( $id );
    return redirect ()->to ( base_url ( 'loyalty-programs' ) )->with ( 'success', 'Reward deleted' );
  }

  public function getCounts()
  {
    $pending = $this->orderModel->where ( 'order_status !=', 'delivered' )->countAllResults ();
    $delivered = $this->orderModel->where ( 'order_status', 'delivered' )->countAllResults ();

    return $this->response->setJSON ( [
      'pending'   => $pending,
      'delivered' => $delivered
    ] );
  }
}