<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Models\CommunityPostModel;
use App\Models\CommunityMediaModel;
use App\Models\CommunityChannelModel;
use App\Models\CommunityReportModel;
use App\Models\CommunityCommentModel;
use App\Models\CommunityChannelJoinModel;
use App\Models\CommunitySaveModel;
use App\Models\CommunityFollowModel;
use App\Models\CommunityPostDislikeModel;
use App\Models\CommunityCommentDislikeModel;
use App\Models\CommunityLikeModel;
use App\Models\User;
use App\Libraries\PushNotify;
use Config\Database;
use Exception;

class CommunityAdminController extends BaseController
{
    protected $postModel;
    protected $channelModel;
    protected $reportModel;
    protected $mediaModel;
    protected $commentModel;
    protected $channelJoinModel;
    protected $saveModel;
    protected $followModel;
    protected $postDislikeModel;
    protected $commentDislikeModel;
    protected $request;
    protected $pushNotify;
    protected $likeModel;

    public function __construct()
    {
        $this->postModel           = new CommunityPostModel();
        $this->channelModel        = new CommunityChannelModel();
        $this->reportModel         = new CommunityReportModel();
        $this->mediaModel          = new CommunityMediaModel();
        $this->commentModel        = new CommunityCommentModel();
        $this->channelJoinModel    = new CommunityChannelJoinModel();
        $this->saveModel           = new CommunitySaveModel();
        $this->followModel         = new CommunityFollowModel();
        $this->postDislikeModel    = new CommunityPostDislikeModel();
        $this->commentDislikeModel = new CommunityCommentDislikeModel();
        $this->likeModel           = new CommunityLikeModel();
        $this->request             = \Config\Services::request();
        $this->pushNotify          = new PushNotify();
    }

    public function viewPost($id)
    {
        $filter = $this->request->getVar('filter') ?? 'all';
        $post   = $this->postModel->select('community_posts.*, COALESCE(employees.name, service_providers.name, users.name, "Unknown User") as name, COALESCE(employees.profile, service_providers.profile, users.profile) as profile, community_channels.name as channel_name')
            ->join('users', "users.id = community_posts.user_id AND (community_posts.user_type = 'user' OR community_posts.user_type = 'client' OR community_posts.user_type IS NULL)", 'left')
            ->join('service_providers', "service_providers.id = community_posts.user_id AND community_posts.user_type = 'provider'", 'left')
            ->join('employees', "employees.id = community_posts.user_id AND community_posts.user_type = 'admin'", 'left')
            ->join('community_channels', 'community_channels.id = community_posts.channel_id', 'left')
            ->find($id);

        if (!$post) {
            return redirect()->to(base_url('community/posts'))->with('error', 'Post not found');
        }

        // Calculate engagement counts for the post
        $post['likes_count']    = $this->likeModel->where('post_id', $id)->countAllResults();
        $post['dislikes_count'] = $this->postDislikeModel->where('post_id', $id)->countAllResults();

        $data['post']  = $post;
        $data['media'] = $this->mediaModel->where('post_id', $id)->findAll();

        $query       = $this->commentModel->select('community_post_comments.*, COALESCE(employees.name, service_providers.name, users.name, "Unknown User") as name, COALESCE(employees.profile, service_providers.profile, users.profile) as profile')
            ->join('users', "users.id = community_post_comments.user_id AND (community_post_comments.user_type = 'user' OR community_post_comments.user_type = 'client' OR community_post_comments.user_type IS NULL)", 'left')
            ->join('service_providers', "service_providers.id = community_post_comments.user_id AND community_post_comments.user_type = 'provider'", 'left')
            ->join('employees', "employees.id = community_post_comments.user_id AND community_post_comments.user_type = 'admin'", 'left')
            ->where('post_id', $id);
        $allComments = $query->orderBy('created_at', 'ASC')
            ->findAll();

        $comments   = [];
        $commentMap = [];

        foreach ($allComments as $comment) {
            $comment['replies']         = [];
            $commentMap[$comment['id']] = $comment;
        }

        foreach ($allComments as $comment) {
            if ($comment['parent_comment_id'] && isset($commentMap[$comment['parent_comment_id']])) {
                $commentMap[$comment['parent_comment_id']]['replies'][] = &$commentMap[$comment['id']];
            } elseif (!$comment['parent_comment_id']) {
                $comments[] = &$commentMap[$comment['id']];
            }
        }

        $data['comments']             = array_reverse($comments); // Newest top-level comments first
        $data['comments_total_count'] = count($allComments);
        $data['reports_count']        = $this->reportModel->where('post_id', $id)->countAllResults();
        $data['current_filter']       = $filter;

        return view('admin/community/community_post_detail', $data);
    }

    public function viewPostReports($id)
    {
        $post = $this->postModel->select('community_posts.*, COALESCE(employees.name, service_providers.name, users.name, "Unknown User") as name, COALESCE(employees.profile, service_providers.profile, users.profile) as profile')
            ->join('users', "users.id = community_posts.user_id AND (community_posts.user_type = 'user' OR community_posts.user_type = 'client' OR community_posts.user_type IS NULL)", 'left')
            ->join('service_providers', "service_providers.id = community_posts.user_id AND community_posts.user_type = 'provider'", 'left')
            ->join('employees', "employees.id = community_posts.user_id AND community_posts.user_type = 'admin'", 'left')
            ->find($id);

        if (!$post) {
            return redirect()->to(base_url('community/posts'))->with('error', 'Post not found');
        }

        $data['post']    = $post;
        $data['media']   = $this->mediaModel->where('post_id', $id)->first();
        $data['reports'] = $this->reportModel->select('community_post_reports.*, COALESCE(employees.name, service_providers.name, users.name, "Unknown Reporter") as name, COALESCE(employees.profile, service_providers.profile, users.profile) as profile, community_post_reports.user_type')
            ->join('users', "users.id = community_post_reports.user_id AND (community_post_reports.user_type = 'user' OR community_post_reports.user_type = 'client' OR community_post_reports.user_type IS NULL)", 'left')
            ->join('service_providers', "service_providers.id = community_post_reports.user_id AND community_post_reports.user_type = 'provider'", 'left')
            ->join('employees', "employees.id = community_post_reports.user_id AND community_post_reports.user_type = 'admin'", 'left')
            ->where('post_id', $id)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('admin/community/community_post_reports', $data);
    }

    public function channels()
    {
        $search = $this->request->getVar('search');

        $query = $this->channelModel;
        if ($search) {
            $query->groupStart()
                ->like('name', $search)
                ->orLike('description', $search)
                ->orLike('handle', $search)
                ->groupEnd();
        }

        $channels = $query->findAll();

        foreach ($channels as &$channel) {
            $channel['member_count'] = $this->channelJoinModel->where('channel_id', $channel['id'])->countAllResults();
        }

        $data['channels'] = $channels;
        $data['request']  = $this->request;

        // Engagement stats
        $posts                    = $this->postModel->select('SUM(likes_count) as total_likes, SUM(comments_count) as total_comments')->first();
        $data['total_engagement'] = ($posts['total_likes'] ?? 0) + ($posts['total_comments'] ?? 0);

        return view('admin/community/community_channels', $data);
    }

    public function viewChannel($id)
    {
        $channel = $this->channelModel->find($id);
        if (!$channel) {
            return redirect()->to(base_url('community/channels'))->with('error', 'Channel not found');
        }

        $data['channel']      = $channel;
        $data['member_count'] = $this->channelJoinModel->where('channel_id', $id)->countAllResults();

        // Fetch posts for this channel
        $search = $this->request->getVar('search');
        $query  = $this->postModel
            ->select('community_posts.*, COALESCE(employees.name, service_providers.name, users.name, "Unknown User") as name, COALESCE(employees.profile, service_providers.profile, users.profile) as profile')
            ->join('users', "users.id = community_posts.user_id AND (community_posts.user_type = 'user' OR community_posts.user_type = 'client' OR community_posts.user_type IS NULL)", 'left')
            ->join('service_providers', "service_providers.id = community_posts.user_id AND community_posts.user_type = 'provider'", 'left')
            ->join('employees', "employees.id = community_posts.user_id AND community_posts.user_type = 'admin'", 'left')
            ->where('community_posts.channel_id', $id)
            ->where('community_posts.status', 'active');

        if ($search) {
            $query->groupStart()
                ->like('community_posts.post_text', $search)
                ->orLike('users.name', $search)
                ->orLike('service_providers.name', $search)
                ->orLike('employees.name', $search)
                ->groupEnd();
        }

        $data['posts']   = $query->orderBy('community_posts.id', 'DESC')->findAll();
        $data['request'] = $this->request;

        foreach ($data['posts'] as &$post) {
            $post['media'] = $this->mediaModel->where('post_id', $post['id'])->findAll();
        }

        return view('admin/community/community_channel_view', $data);
    }

    public function updateChannel($id)
    {
        $data = [
            'name'        => $this->request->getPost('name'),
            'handle'      => $this->request->getPost('handle'),
            'description' => $this->request->getPost('description'),
            'status'      => $this->request->getPost('status') ? 1 : 0,
            'is_private'  => $this->request->getPost('is_private') ? 1 : 0,
        ];

        $img = $this->request->getFile('icon');
        if ($img && $img->isValid() && !$img->hasMoved()) {
            $newName = $img->getRandomName();
            $img->move(\FCPATH . 'public/uploads/community/icons', $newName);
            $data['icon'] = $newName;
        }

        $this->channelModel->update($id, $data);

        return redirect()->to(base_url('community/channels'))->with('success', 'Channel updated successfully');
    }

    public function addChannel()
    {
        $data = [
            'name'        => $this->request->getPost('name'),
            'handle'      => $this->request->getPost('handle'),
            'description' => $this->request->getPost('description'),
            'status'      => $this->request->getPost('status') ? 1 : 0,
            'is_private'  => $this->request->getPost('is_private') ? 1 : 0,
        ];

        $img = $this->request->getFile('icon');
        if ($img && $img->isValid() && !$img->hasMoved()) {
            $newName = $img->getRandomName();
            $img->move(\FCPATH . 'public/uploads/community/icons', $newName);
            $data['icon'] = $newName;
        } else {
            $data['icon'] = 'default_icon.png';
        }

        $this->channelModel->insert($data);

        return redirect()->to(base_url('community/channels'))->with('success', 'Channel added successfully');
    }

    public function deleteChannel($id)
    {
        $this->channelModel->delete($id);
        return redirect()->to(base_url('community/channels'))->with('success', 'Channel deleted');
    }

    public function addPost()
    {
        $userModel        = new User();
        $data['user']     = (array) $userModel->getUser(session()->get('user')->id ?? 1);
        $data['channels'] = $this->channelModel->where('status', 1)->findAll();
        $data['post']     = null;
        $data['title']    = 'Compose Community Post';

        return view('admin/community/community_post_form', $data);
    }

    public function editPost($id)
    {
        $post = $this->postModel->find($id);
        if (!$post) {
            return redirect()->to(base_url('community/posts'))->with('error', 'Post not found');
        }

        $userModel        = new User();
        $data['user']     = (array) $userModel->getUserById($post['user_id']);
        $data['post']     = $post;
        $data['channels'] = $this->channelModel->findAll();
        $data['media']    = $this->mediaModel->where('post_id', $id)->findAll();
        $data['title']    = 'Edit Community Post';

        return view('admin/community/community_post_form', $data);
    }

    public function savePost()
    {
        $data = [
            'channel_id'      => $this->request->getPost('channel_id'),
            'user_id'         => session()->get('user')->id ?? 1,
            'user_type'       => 'admin',
            'category'        => $this->request->getPost('category') ?? 'post',
            'post_text'       => $this->request->getPost('post_text'),
            'post_type'       => 'text',
            'status'          => 'active',
            'is_pinned'       => $this->request->getPost('is_pinned') ? 1 : 0,
            'visibility'      => $this->request->getPost('visibility') ?? 'public',
            'scheduled_time'  => $this->request->getPost('scheduled_time') ?: null,
            'comment_section' => $this->request->getPost('comment_section') ? 1 : 0,
        ];

        // Mark as scheduled if time is set for the future
        if ($data['scheduled_time'] && strtotime($data['scheduled_time']) > time()) {
            $data['status'] = 'scheduled';
        }

        if ($data['is_pinned']) {
            // Unpin all other posts globally (as only one post can be pinned at any time)
            $this->postModel->where('is_pinned', 1)->set(['is_pinned' => 0])->update();
        }

        $postId = $this->postModel->insert($data);
        if (!$postId) {
            return redirect()->back()->with('error', 'Failed to create post');
        }

        // Handle Media
        $allFiles = $this->request->getFileMultiple('media') ?? [];
        if (!is_array($allFiles)) {
            $allFiles = [$allFiles];
        }

        if (!empty($allFiles)) {
            foreach ($allFiles as $img) {
                if ($img && $img->isValid() && !$img->hasMoved()) {
                    $mimeType  = $img->getMimeType();
                    $mediaType = strpos($mimeType, 'video') !== false ? 'video' : 'image';
                    $newName   = $img->getRandomName();

                    $img->move(FCPATH . 'public/uploads/community/media', $newName);

                    $this->mediaModel->insert([
                        'post_id'    => $postId,
                        'media_type' => $mediaType,
                        'media_url'  => base_url('public/uploads/community/media/' . $newName)
                    ]);
                }
            }
        }

        // Notify Channel Members for Admin Post
        try {
            $channel     = $this->channelModel->find($data['channel_id']);
            $channelName = $channel['name'] ?? 'Channel';
            if ($data['status'] != 'scheduled') {
                $this->notifyChannelMembers(
                    $data['channel_id'],
                    $postId,
                    "Don’t miss this! A new announcement has been posted in #{$channelName}.",
                    "By Petsfolio: Important Update",
                    $data['visibility']
                );
            }
        } catch (Exception $e) {
            \log_message('error', 'Push Notify Error (Admin Post): ' . $e->getMessage());
        }

        return redirect()->to(base_url('community/posts'))->with('success', 'Post published successfully');
    }

    private function notifyChannelMembers($channelId, $postId, $message, $title = "New Post", $targetAudience = 'all')
    {
        try {
            $members = $this->channelJoinModel->where('channel_id', $channelId)->findAll();
            if (!empty($members)) {
                $memberIds = array_column($members, 'user_id');
                $db        = Database::connect();
                $fcmTokens = [];

                if ($targetAudience === 'all' || $targetAudience === 'public' || $targetAudience === 'user' || $targetAudience === 'client') {
                    $tokens = $db->table('users')
                        ->select('device_token')
                        ->whereIn('id', $memberIds)
                        ->where('device_token !=', '')
                        ->get()->getResultArray();
                    $fcmTokens = array_merge($fcmTokens, array_column($tokens, 'device_token'));
                }

                if ($targetAudience === 'all' || $targetAudience === 'public' || $targetAudience === 'provider') {
                    $tokens = $db->table('service_providers')
                        ->select('device_token')
                        ->whereIn('id', $memberIds)
                        ->where('device_token !=', '')
                        ->get()->getResultArray();
                    $fcmTokens = array_merge($fcmTokens, array_column($tokens, 'device_token'));
                }

                $fcmTokens = array_unique(array_filter($fcmTokens));

                if (!empty($fcmTokens)) {
                    $this->pushNotify->sendMulticast(
                        $fcmTokens,
                        $title,
                        $message,
                        'PostDetailScreen',
                        ['postId' => $postId]
                    );
                }
            }
        } catch (Exception $e) {
            \log_message('error', 'Push Notify Error (Admin Channel Notify): ' . $e->getMessage());
        }
    }

    public function updatePost($id)
    {
        $data = [
            'channel_id'      => $this->request->getPost('channel_id'),
            'category'        => $this->request->getPost('category') ?? 'post',
            'post_text'       => $this->request->getPost('post_text'),
            'is_pinned'       => $this->request->getPost('is_pinned') ? 1 : 0,
            'visibility'      => $this->request->getPost('visibility') ?? 'public',
            'scheduled_time'  => $this->request->getPost('scheduled_time') ?: null,
            'comment_section' => $this->request->getPost('comment_section') ? 1 : 0,
        ];

        // Update status based on scheduling
        if ($data['scheduled_time'] && strtotime($data['scheduled_time']) > time()) {
            $data['status'] = 'scheduled';
        } else {
            $data['status'] = 'active';
        }

        if ($data['is_pinned']) {
            // Unpin all other posts globally (as only one post can be pinned at any time)
            $this->postModel->where('is_pinned', 1)->set(['is_pinned' => 0])->update();
        }

        $this->postModel->update($id, $data);

        // Handle Deletions of existing media
        $deletedMediaIds = $this->request->getPost('deleted_media_ids');
        if ($deletedMediaIds) {
            $ids = explode(',', $deletedMediaIds);
            foreach ($ids as $mediaId) {
                if (empty($mediaId)) continue;
                $media = $this->mediaModel->find($mediaId);
                if ($media) {
                    // Extract filename from URL
                    $filename = basename($media['media_url']);

                    // Check directories
                    $mediaPath = FCPATH . 'public/uploads/community/media/' . $filename;
                    $postsPath = FCPATH . 'public/uploads/community/posts/' . $filename;

                    if (file_exists($mediaPath) && is_file($mediaPath)) {
                        unlink($mediaPath);
                    } elseif (file_exists($postsPath) && is_file($postsPath)) {
                        unlink($postsPath);
                    }

                    $this->mediaModel->delete($mediaId);
                }
            }
        }

        // Handle New Media Uploads during update
        $allFiles = $this->request->getFileMultiple('media') ?? [];
        if (!is_array($allFiles)) {
            $allFiles = [$allFiles];
        }

        if (!empty($allFiles)) {
            foreach ($allFiles as $img) {
                if ($img && $img->isValid() && !$img->hasMoved()) {
                    $mimeType  = $img->getMimeType();
                    $mediaType = strpos($mimeType, 'video') !== false ? 'video' : 'image';
                    $newName   = $img->getRandomName();

                    $img->move(FCPATH . 'public/uploads/community/media', $newName);

                    $this->mediaModel->insert([
                        'post_id'    => $id,
                        'media_type' => $mediaType,
                        'media_url'  => base_url('public/uploads/community/media/' . $newName)
                    ]);
                }
            }
        }

        return redirect()->to(base_url('community/posts'))->with('success', 'Post updated successfully');
    }

    public function posts()
    {
        $status    = $this->request->getVar('status') ?? 'admin';
        $search    = $this->request->getVar('search');
        $channelId = $this->request->getVar('channel_id');

        // Main Query
        $query = $this->postModel
            ->select('community_posts.*, COALESCE(employees.name, service_providers.name, users.name, "Unknown User") as name, COALESCE(employees.profile, service_providers.profile, users.profile) as profile, community_channels.name as channel_name')
            ->join('users', "users.id = community_posts.user_id AND (community_posts.user_type = 'user' OR community_posts.user_type = 'client' OR community_posts.user_type IS NULL)", 'left')
            ->join('service_providers', "service_providers.id = community_posts.user_id AND community_posts.user_type = 'provider'", 'left')
            ->join('employees', "employees.id = community_posts.user_id AND community_posts.user_type = 'admin'", 'left')
            ->join('community_channels', 'community_channels.id = community_posts.channel_id', 'left')
            ->where('community_posts.status !=', 'deleted');

        if ($status == 'admin') {
            $query->where('community_posts.user_type', 'admin')->where('community_posts.status', 'active');
        } elseif ($status == 'user' || $status == 'client') {
            $query->whereIn('community_posts.user_type', ['user', 'client'])->where('community_posts.status', 'active');
        } elseif ($status == 'provider') {
            $query->where('community_posts.user_type', 'provider')->where('community_posts.status', 'active');
        } elseif ($status == 'scheduled') {
            $query->where('community_posts.status', 'scheduled');
        } elseif ($status == 'questions') {
            $query->where('community_posts.category', 'question')->where('community_posts.status', 'active');
        } else {
            $query->where('community_posts.status', 'active');
        }

        if ($search) {
            $query->groupStart()
                ->like('community_posts.post_text', $search)
                ->orLike('users.name', $search)
                ->orLike('service_providers.name', $search)
                ->orLike('employees.name', $search)
                ->groupEnd();
        }

        if ($channelId) {
            $query->where('community_posts.channel_id', $channelId);
        }

        $data['posts']    = $query->orderBy('community_posts.id', 'DESC')->findAll();
        $data['channels'] = $this->channelModel->findAll();
        foreach ($data['posts'] as &$post) {
            $post['media']          = $this->mediaModel->where('post_id', $post['id'])->findAll();
            $post['likes_count']    = $this->likeModel->where('post_id', $post['id'])->countAllResults();
            $post['comments_count'] = $this->commentModel->where('post_id', $post['id'])->countAllResults();
        }

        // Calculate counts for tabs independently with fresh instances to avoid builder pollution
        $adminCountBuilder = new CommunityPostModel();
        $adminCountBuilder->where('status', 'active')->where('user_type', 'admin');
        if ($search) $adminCountBuilder->groupStart()->like('post_text', $search)->groupEnd();
        if ($channelId) $adminCountBuilder->where('channel_id', $channelId);
        $data['admin_posts_count'] = $adminCountBuilder->countAllResults();

        $userCountBuilder = new CommunityPostModel();
        $userCountBuilder->where('status', 'active')->whereIn('user_type', ['user', 'client']);
        if ($search) $userCountBuilder->groupStart()->like('post_text', $search)->groupEnd();
        if ($channelId) $userCountBuilder->where('channel_id', $channelId);
        $data['user_posts_count'] = $userCountBuilder->countAllResults();

        $providerCountBuilder = new CommunityPostModel();
        $providerCountBuilder->where('status', 'active')->where('user_type', 'provider');
        if ($search) $providerCountBuilder->groupStart()->like('post_text', $search)->groupEnd();
        if ($channelId) $providerCountBuilder->where('channel_id', $channelId);
        $data['provider_posts_count'] = $providerCountBuilder->countAllResults();

        $scheduledCountBuilder = new CommunityPostModel();
        $scheduledCountBuilder->where('status', 'scheduled');
        if ($search) $scheduledCountBuilder->groupStart()->like('post_text', $search)->groupEnd();
        if ($channelId) $scheduledCountBuilder->where('channel_id', $channelId);
        $data['scheduled_posts_count'] = $scheduledCountBuilder->countAllResults();

        $questionCountBuilder = new CommunityPostModel();
        $questionCountBuilder->where('status', 'active')->where('category', 'question');
        if ($search) $questionCountBuilder->groupStart()->like('post_text', $search)->groupEnd();
        if ($channelId) $questionCountBuilder->where('channel_id', $channelId);
        $data['questions_count'] = $questionCountBuilder->countAllResults();

        // Fetch Metrics
        $totalPosts   = $this->postModel->where('status !=', 'deleted')->countAllResults();
        $totalReports = $this->reportModel->countAllResults();

        $totalLikes        = $this->likeModel->countAllResults();
        $totalComments     = $this->commentModel->countAllResults();
        $totalInteractions = $totalLikes + $totalComments;

        $activeUsers = (new CommunityPostModel())
            ->whereIn('user_type', ['user', 'client'])
            ->where('status !=', 'deleted')
            ->select('user_id')->distinct()->countAllResults();

        $activeProviders = (new CommunityPostModel())
            ->where('user_type', 'provider')
            ->where('status !=', 'deleted')
            ->select('user_id')->distinct()->countAllResults();

        $data['stats'] = [
            'total_posts'        => $totalPosts,
            'active_users'       => $activeUsers,
            'active_providers'   => $activeProviders,
            'total_interactions' => $totalInteractions,
            'reporting_rate'     => ($totalPosts > 0) ? number_format(($totalReports / $totalPosts) * 100, 1) . '%' : '0.0%'
        ];

        // Fetch Last Hour Posts for Activity Feed
        $data['lastHourPosts'] = $this->postModel
            ->select('community_posts.*, COALESCE(employees.name, service_providers.name, users.name, "Unknown User") as name, COALESCE(employees.profile, service_providers.profile, users.profile) as profile')
            ->join('users', "users.id = community_posts.user_id AND (community_posts.user_type = 'user' OR community_posts.user_type = 'client' OR community_posts.user_type IS NULL)", 'left')
            ->join('service_providers', "service_providers.id = community_posts.user_id AND community_posts.user_type = 'provider'", 'left')
            ->join('employees', "employees.id = community_posts.user_id AND community_posts.user_type = 'admin'", 'left')
            ->where('community_posts.created_at >=', date('Y-m-d H:i:s', strtotime('-1 hour')))
            ->where('community_posts.status !=', 'deleted')
            ->orderBy('community_posts.created_at', 'DESC')
            ->limit(5)
            ->findAll();

        $data['status']  = $status;
        $data['request'] = $this->request;

        return view('admin/community/community_posts', $data);
    }

    public function approvePost($id)
    {
        $this->postModel->update($id, ['status' => 'active']);
        return redirect()->to(base_url('community/posts?status=pending'))->with('success', 'Post approved');
    }

    public function togglePin($id)
    {
        $post = $this->postModel->find($id);

        if (!$post) {
            return redirect()->back()->with('error', 'Post not found');
        }

        $newStatus = $post['is_pinned'] ? 0 : 1;

        if ($newStatus == 1) {
            // Unpin all other posts globally (using where to satisfy CI database protection)
            $this->postModel->where('is_pinned', 1)->set(['is_pinned' => 0])->update();
        }

        $this->postModel->update($id, [
            'is_pinned' => $newStatus
        ]);

        return redirect()->back()->with('success', 'Post pin status updated');
    }

    public function reportComment($id)
    {
        $comment = $this->commentModel->find($id);
        if (!$comment) {
            return redirect()->back()->with('error', 'Comment not found');
        }

        $data = [
            'post_id'    => $comment['post_id'],
            'comment_id' => $id,
            'user_id'    => session()->get('user')->id ?? 1,
            'user_type'  => 'admin',
            'reason'     => $this->request->getGet('reason') ?? $this->request->getPost('reason') ?? 'Reported by Administrator',
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->reportModel->insert($data);
        return redirect()->back()->with('success', 'Comment reported successfully');
    }

    public function addDirectComment()
    {
        $postId = $this->request->getPost('post_id');
        $commentText = $this->request->getPost('comment');

        if (!$postId || !$commentText) {
            return redirect()->back()->with('error', 'Post ID and comment are required');
        }

        $data = [
            'post_id'    => $postId,
            'user_id'    => session()->get('user')->id ?? 1,
            'user_type'  => 'admin',
            'comment'    => $commentText,
            'status'     => 'active',
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->commentModel->insert($data);

        // Increment comment count
        $this->postModel
            ->set('comments_count', 'comments_count + 1', false)
            ->where('id', $postId)
            ->update();

        return redirect()->back()->with('success', 'Comment added successfully');
    }

    public function addReply($parentId)
    {
        $parentComment = $this->commentModel->find($parentId);
        if (!$parentComment) {
            return redirect()->back()->with('error', 'Parent comment not found');
        }
        $this->postModel
            ->set('comments_count', 'comments_count + 1', false)
            ->where('id', $parentComment['post_id'])
            ->update();
        $adminCommentText = $this->request->getPost('comment');
        $data             = [
            'post_id'           => $parentComment['post_id'],
            'user_id'           => session()->get('user')->id ?? 1,
            'user_type'         => 'admin',
            'comment'           => $adminCommentText,
            'parent_comment_id' => $parentId,
            'status'            => 'active',
            'created_at'        => date('Y-m-d H:i:s')
        ];

        $this->commentModel->insert($data);

        // Notify the client or provider of the admin's reply
        if ($parentComment['user_type'] != 'admin' && !empty($parentComment['user_id'])) {
            try {
                $db          = Database::connect();
                $deviceToken = null;
                if ($parentComment['user_type'] == 'provider') {
                    $spData = $db->table('service_providers')
                        ->select('name, device_token')
                        ->where('id', $parentComment['user_id'])
                        ->where('device_token !=', '')
                        ->get()->getRowArray();
                    $deviceToken = $spData['device_token'] ?? null;
                } else {
                    $userData = $db->table('users')
                        ->select('name, device_token')
                        ->where('id', $parentComment['user_id'])
                        ->where('device_token !=', '')
                        ->get()->getRowArray();
                    $deviceToken = $userData['device_token'] ?? null;
                }

                if (!empty($deviceToken)) {
                    $this->pushNotify->sendMulticast(
                        [$deviceToken],
                        "Petsfolio replied to your comment",
                        "Petsfolio just replied to your comment in the community.",
                        'PostDetailScreen',
                        ['postId' => $parentComment['post_id']]
                    );
                }
            } catch (Exception $e) {
                \log_message('error', 'Push Notify Error (Admin Reply): ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Reply added successfully');
    }

    public function deletePost($id)
    {
        $this->postModel->update($id, ['status' => 'deleted']);
        return redirect()->back()->with('success', 'Post deleted');
    }

    public function postReports($id)
    {
        $post = $this->postModel->select('community_posts.*, COALESCE(employees.name, service_providers.name, users.name, "Unknown User") as name, COALESCE(employees.profile, service_providers.profile, users.profile) as profile')
            ->join('users', "users.id = community_posts.user_id AND (community_posts.user_type = 'user' OR community_posts.user_type = 'client' OR community_posts.user_type IS NULL)", 'left')
            ->join('service_providers', "service_providers.id = community_posts.user_id AND community_posts.user_type = 'provider'", 'left')
            ->join('employees', "employees.id = community_posts.user_id AND community_posts.user_type = 'admin'", 'left')
            ->find($id);
        if (!$post) {
            return redirect()->to(base_url('community/posts'))->with('error', 'Post not found');
        }

        $data['post']    = $post;
        $data['media']   = $this->mediaModel->where('post_id', $id)->first();
        $data['reports'] = $this->reportModel->select('community_post_reports.*, COALESCE(employees.name, service_providers.name, users.name, "Unknown Reporter") as name, COALESCE(employees.profile, service_providers.profile, users.profile) as profile, community_post_reports.user_type')
            ->join('users', "users.id = community_post_reports.user_id AND (community_post_reports.user_type = 'user' OR community_post_reports.user_type = 'client' OR community_post_reports.user_type IS NULL)", 'left')
            ->join('service_providers', "service_providers.id = community_post_reports.user_id AND community_post_reports.user_type = 'provider'", 'left')
            ->join('employees', "employees.id = community_post_reports.user_id AND community_post_reports.user_type = 'admin'", 'left')
            ->where('post_id', $id)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $data['reports_count'] = $this->reportModel->where('post_id', $id)->countAllResults();

        return view('admin/community/community_post_reports', $data);
    }

    public function reports()
    {
        $search = $this->request->getVar('search');
        $reason = $this->request->getVar('reason');
        $status = $this->request->getVar('status') ?? 'all';

        $query = $this->reportModel
            ->select('community_post_reports.*, community_posts.post_text, community_posts.status as post_status, community_posts.user_type as post_author_type, COALESCE(rep_emp.name, rep_sp.name, rep_u.name, "Unknown Reporter") as name, COALESCE(rep_emp.profile, rep_sp.profile, rep_u.profile) as profile, community_channels.name as channel_name')
            ->join('community_posts', 'community_posts.id = community_post_reports.post_id')
            ->join('users rep_u', "rep_u.id = community_post_reports.user_id AND (community_post_reports.user_type = 'user' OR community_post_reports.user_type = 'client' OR community_post_reports.user_type IS NULL)", 'left')
            ->join('service_providers rep_sp', "rep_sp.id = community_post_reports.user_id AND community_post_reports.user_type = 'provider'", 'left')
            ->join('employees rep_emp', "rep_emp.id = community_post_reports.user_id AND community_post_reports.user_type = 'admin'", 'left')
            ->join('community_channels', 'community_channels.id = community_posts.channel_id', 'left');

        if ($search) {
            $query->groupStart()
                ->like('community_posts.post_text', $search)
                ->orLike('rep_u.name', $search)
                ->orLike('rep_sp.name', $search)
                ->orLike('rep_emp.name', $search)
                ->orLike('community_post_reports.reason', $search)
                ->groupEnd();
        }

        if ($reason) {
            $query->where('community_post_reports.reason', $reason);
        }

        if ($status == 'pending') {
            $query->where('community_posts.status', 'active');
        } elseif ($status == 'resolved') {
            $query->where('community_posts.status', 'blocked');
        }

        $data['reports'] = $query->orderBy('community_post_reports.id', 'DESC')->findAll();
        $data['request'] = $this->request;
        $data['status']  = $status;

        // Dynamic Stats for the dashboard
        $data['active_reports_count'] = $this->reportModel->countAllResults();

        // High priority - filter by more serious reasons if they exist
        $data['high_priority_count'] = $this->reportModel
            ->groupStart()
            ->like('reason', 'hate')
            ->orLike('reason', 'harassment')
            ->orLike('reason', 'illegal')
            ->orLike('reason', 'violence')
            ->groupEnd()
            ->countAllResults();

        // Resolved today stats (using soft-deletes if available, otherwise defaulting to a reasonable value for the UI)
        try {
            $data['resolved_today_count'] = $this->reportModel->onlyDeleted()
                ->where('deleted_at >=', date('Y-m-d 00:00:00'))
                ->countAllResults();
        } catch (\Throwable $e) {
            // If soft-deletes column doesn't exist, use 0 or a placeholder
            $data['resolved_today_count'] = 0;
        }

        return view('admin/community/community_reports', $data);
    }

    public function dismissReport($id)
    {
        $this->reportModel->delete($id);
        return redirect()->back()->with('success', 'Report dismissed');
    }

    public function deleteComment($id)
    {
        $this->commentModel->delete($id);
        return redirect()->back()->with('success', 'Comment removed');
    }

    public function blockPost($postId)
    {
        $this->postModel->update($postId, ['status' => 'blocked']);
        return redirect()->back()->with('success', 'Post blocked and hidden from users');
    }

    public function unblockPost($postId)
    {
        $this->postModel->update($postId, ['status' => 'active']);
        return redirect()->back()->with('success', 'Post unblocked and restored');
    }

    public function dismissAllReports($postId)
    {
        $this->reportModel->where('post_id', $postId)->delete();
        return redirect()->back()->with('success', 'All reports for this post dismissed');
    }
    public function processScheduledPosts()
    {
        $now            = date('Y-m-d H:i:s');
        $scheduledPosts = $this->postModel
            ->where('status', 'scheduled')
            ->where('scheduled_time <=', $now)
            ->findAll();

        $count = 0;
        foreach ($scheduledPosts as $post) {
            // Activate the post
            $this->postModel->update($post['id'], [
                'status'     => 'active',
                'created_at' => $now // Optionally reset created_at to now so it appears fresh in feeds
            ]);

            // Notify Channel Members for Admin Announcements that are now live
            if ($post['user_type'] == 'admin') {
                try {
                    $channel     = $this->channelModel->find($post['channel_id']);
                    $channelName = $channel['name'] ?? 'Channel';
                    $this->notifyChannelMembers(
                        $post['channel_id'],
                        $post['id'],
                        "Don’t miss this! A new announcement has been posted in #{$channelName}.",
                        "By Petsfolio: Important Update",
                        $post['visibility'] ?? 'all'
                    );
                } catch (\Exception $e) {
                    \log_message('error', 'Push Notify Error (Scheduled Cron): ' . $e->getMessage());
                }
            }
            $count++;
        }

        return "Successfully processed and published {$count} scheduled posts.";
    }
}
