<?= $this->extend ( 'admin/includes/header' ); ?>
<?= $this->section ( 'content' ); ?>

<!-- Google Font & Material Icons -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
    rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
    rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>

<script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    sans: ['Plus Jakarta Sans', 'sans-serif'],
                },
                colors: {
                    brand: {
                        primary: '#4f46e5',
                        secondary: '#6366f1',
                        accent: '#8b5cf6',
                        dark: '#1e293b',
                    }
                }
            }
        }
    }
</script>

<div class="min-h-screen pb-20">
    <div class="position-relative iq-banner">
        <?php echo $this->include ( 'admin/nav_bar' ) ?>
    </div>
    <div class="p-4" style="margin-top: -60px;">
        <div class="mx-auto mb-4">
            <div
                class="bg-indigo-600 rounded-[2.5rem] p-10 text-white relative overflow-hidden shadow-2xl shadow-indigo-200">
                <div
                    class="absolute top-0 right-0 w-[400px] h-[400px] bg-white opacity-[0.05] rounded-full -mr-32 -mt-32">
                </div>
                <div class="relative z-10 flex flex-col lg:flex-row justify-between gap-8">
                    <div class="flex-grow">
                        <div class="flex items-center gap-6 mb-8">
                            <div
                                class="w-16 h-16 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/20">
                                <span class="material-symbols-outlined text-3xl">analytics</span>
                            </div>
                            <div>
                                <h1 class="text-4xl text-white font-900 tracking-tight leading-none mb-2">All Reports
                                </h1>
                                <p class="text-white/70 font-semibold tracking-wide">Welcome back! Here's your community
                                    moderation overview</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-4 min-w-[280px]">
                        <div
                            class="bg-white/10 backdrop-blur-md p-6 rounded-[1.8rem] border border-white/20 text-center flex flex-col justify-center h-28">
                            <span class="text-3xl font-900 leading-none mb-1"><?= date ( 'M Y' ) ?></span>
                            <span class="text-[10px] font-900 uppercase tracking-widest opacity-60">Current Moderation
                                Period</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto">
            <!-- Section Header & Filters -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div class="flex items-center gap-2.5">
                    <div class="w-1.5 h-6 bg-brand-primary rounded-full"></div>
                    <h2 class="text-xl font-900 text-slate-900 tracking-tight">Recent Flags Feed</h2>
                </div>
                <div class="flex p-1 bg-white border border-slate-200/60 rounded-xl shadow-sm">
                    <a href="<?= base_url ( 'community/reports?status=all' ) ?>"
                        class="px-5 py-2 <?= ( !isset ( $status ) || $status == 'all' || $status == '' ) ? 'bg-slate-900 text-white shadow-md shadow-slate-200' : 'text-slate-500 hover:bg-slate-50' ?> rounded-lg text-[9px] font-900 uppercase tracking-widest transition-all">All</a>
                    <a href="<?= base_url ( 'community/reports?status=pending' ) ?>"
                        class="px-5 py-2 <?= ( ( $status ?? '' ) == 'pending' ) ? 'bg-slate-900 text-white shadow-md shadow-slate-200' : 'text-slate-500 hover:bg-slate-50' ?> rounded-lg text-[9px] font-900 uppercase tracking-widest transition-all">Reported</a>
                    <a href="<?= base_url ( 'community/reports?status=resolved' ) ?>"
                        class="px-5 py-2 <?= ( ( $status ?? '' ) == 'resolved' ) ? 'bg-slate-900 text-white shadow-md shadow-slate-200' : 'text-slate-500 hover:bg-slate-50' ?> rounded-lg text-[9px] font-900 uppercase tracking-widest transition-all">Blocked</a>
                </div>
            </div>

            <!-- Feed Items -->
            <div class="space-y-3">
                <?php foreach ( $reports as $report ) : ?>
                    <?php
                    $badgeColor = 'bg-rose-50 text-rose-600';
                    $icon       = 'warning';
                    $iconBg     = 'bg-rose-100 text-rose-500';

                    if ( stripos ( $report['reason'], 'spam' ) !== false ) {
                        $badgeColor = 'bg-indigo-50 text-indigo-600';
                        $icon       = 'chat_bubble';
                        $iconBg     = 'bg-indigo-100 text-indigo-500';
                    }
                    elseif ( stripos ( $report['reason'], 'misinformation' ) !== false ) {
                        $badgeColor = 'bg-cyan-50 text-cyan-600';
                        $icon       = 'verified';
                        $iconBg     = 'bg-cyan-100 text-cyan-500';
                    }
                    ?>
                    <div
                        class="feed-item bg-white p-4 rounded-[1.5rem] border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 group/item">
                        <!-- Header with Title and Meta -->
                        <div
                            class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-3 pb-2 border-b border-slate-100">
                            <div class="flex items-center gap-2 flex-wrap">
                                <div class="w-7 h-7 <?= $iconBg ?> rounded-lg flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-base"><?= $icon ?></span>
                                </div>
                                <span class="text-xs font-700 text-slate-500 uppercase tracking-wide">Category:</span>
                                <span class="px-2 py-0.5 bg-slate-100 text-slate-700 rounded-md text-[11px] font-800">
                                    <?= esc ( $report['title'] ?? 'General Report' ) ?>
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[14px] text-slate-400">schedule</span>
                                <span class="text-[11px] font-medium text-slate-500">
                                    <?php
                                    $diff = time () - strtotime ( $report['created_at'] );
                                    if ( $diff < 60 ) echo "Just now";
                                    elseif ( $diff < 3600 ) echo round ( $diff / 60 ) . " minutes ago";
                                    elseif ( $diff < 86400 ) echo round ( $diff / 3600 ) . " hours ago";
                                    else echo round ( $diff / 86400 ) . " days ago";
                                    ?>
                                </span>
                            </div>
                        </div>

                        <!-- Post Content -->
                        <div class="mb-3">
                            <div class="flex items-start gap-2">
                                <span
                                    class="material-symbols-outlined text-[16px] text-slate-400 mt-0.5">format_quote</span>
                                <div class="flex-1">
                                    <span class="text-[10px] font-700 text-slate-400 uppercase tracking-wider">Post
                                        Content</span>
                                    <p class="text-sm font-600 text-slate-800 leading-relaxed mt-0.5">
                                        "<?= nl2br ( esc ( mb_strimwidth ( $report['post_text'], 0, 120, '...' ) ) ) ?>"
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Report Details Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-3 p-3 bg-slate-50/50 rounded-xl">
                            <!-- Reason -->
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[14px] text-slate-500">gavel</span>
                                <span class="text-[10px] font-700 text-slate-500 uppercase tracking-wider">Reason:</span>
                                <span class="px-2 py-0.5 <?= $badgeColor ?> rounded-md text-[11px] font-800">
                                    <?= ucfirst ( $report['reason'] ) ?>
                                </span>
                            </div>

                            <!-- Reporter -->
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[14px] text-slate-500">person_outline</span>
                                <span class="text-[10px] font-700 text-slate-500 uppercase tracking-wider">Reported
                                    By:</span>
                                <span class="text-[12px] font-800 text-slate-900"><?= esc ( $report['name'] ) ?></span>
                            </div>

                            <!-- Channel -->
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[14px] text-slate-500">forum</span>
                                <span class="text-[10px] font-700 text-slate-500 uppercase tracking-wider">Channel:</span>
                                <span
                                    class="text-[12px] font-600 text-brand-primary"><?= esc ( $report['channel_name'] ?? 'General' ) ?></span>
                            </div>

                            <!-- Status -->
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[14px] text-slate-500">verified</span>
                                <span class="text-[10px] font-700 text-slate-500 uppercase tracking-wider">Post
                                    Status:</span>
                                <span
                                    class="text-[11px] font-700 <?= ( $report['post_status'] ?? 'active' ) == 'active' ? 'text-emerald-600' : 'text-rose-600' ?>">
                                    <?= ucfirst ( $report['post_status'] ?? 'Active' ) ?>
                                </span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-slate-100">
                            <a href="<?= base_url ( 'community/post/' . $report['post_id'] ) ?>"
                                class="px-4 py-2 bg-slate-900 text-white rounded-lg text-[11px] font-900 uppercase tracking-widest hover:bg-brand-primary transition-all flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">visibility</span>
                                View Post
                            </a>

                            <?php if ( ( $report['post_status'] ?? 'active' ) != 'blocked' ) : ?>
                                <a href="<?= base_url ( 'community/blockPost/' . $report['post_id'] ) ?>"
                                    class="px-4 py-2 bg-amber-50 text-amber-700 rounded-lg text-[11px] font-900 uppercase tracking-widest hover:bg-amber-600 hover:text-white transition-all flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">block</span>
                                    Block
                                </a>
                            <?php else : ?>
                                <a href="<?= base_url ( 'community/unblockPost/' . $report['post_id'] ) ?>"
                                    class="px-4 py-2 bg-emerald-50 text-emerald-700 rounded-lg text-[11px] font-900 uppercase tracking-widest hover:bg-emerald-600 hover:text-white transition-all flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">check_circle</span>
                                    Unblock
                                </a>
                            <?php endif; ?>

                            <a href="<?= base_url ( 'community/deletePost/' . $report['post_id'] ) ?>"
                                class="px-4 py-2 bg-rose-50 text-rose-600 rounded-lg text-[11px] font-900 uppercase tracking-widest hover:bg-rose-500 hover:text-white transition-all flex items-center gap-1"
                                onclick="return confirm('Are you sure you want to delete this post?')">
                                <span class="material-symbols-outlined text-[14px]">delete</span>
                                Delete
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if ( empty ( $reports ) ) : ?>
                    <div class="bg-white p-20 rounded-[3rem] border border-slate-100 text-center">
                        <div
                            class="w-20 h-20 bg-emerald-100 rounded-3xl flex items-center justify-center text-emerald-500 mx-auto mb-6">
                            <span class="material-symbols-outlined text-[40px] fill">check_circle</span>
                        </div>
                        <h3 class="text-2xl font-800 text-slate-900 mb-2">All Clean!</h3>
                        <p class="text-slate-400 font-semibold italic">No active reports currently awaiting review.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: #fcfdfe;
        background-image:
            radial-gradient(at 0% 0%, rgba(79, 70, 229, 0.05) 0px, transparent 50%),
            radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.05) 0px, transparent 50%),
            radial-gradient(at 100% 100%, rgba(99, 102, 241, 0.05) 0px, transparent 50%),
            radial-gradient(at 0% 100%, rgba(79, 70, 229, 0.05) 0px, transparent 50%);
        color: #1e293b;
        min-height: 100vh;
    }

    .feed-item {
        transition: all 0.2s ease;
    }

    .feed-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 20px -5px rgba(0, 0, 0, 0.08);
    }

    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }

    .fill {
        font-variation-settings: 'FILL' 1;
    }
</style>

<?= $this->endSection (); ?>