<?= $this->extend('admin/includes/header'); ?>

<?= $this->section('content'); ?>

<?php
$service_id = (int)$service_id;
$categories = $organized_questions;
$totalPossible = $total_possible_score;

function ratingOptions($weight, $isSimple = false, $optionsStr = '') {
    $weight = (float)$weight;
    $parsedOptions = [];
    if (!empty($optionsStr)) {
        if (preg_match_all('/(?:^|,\s*)(Bad|Poor|Average|Good|Excellent)\s*:\s*(.*?)(?=(?:,\s*(?:Bad|Poor|Average|Good|Excellent)\s*:|$))/s', $optionsStr, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $m) {
                $key = trim($m[1]);
                $val = trim($m[2]);
                $val = str_replace(["\r", "\n", "\\r", "\\n"], "", $val);
                $parsedOptions[$key] = $val;
            }
        } else {
            $parts = explode(',', $optionsStr);
            foreach ($parts as $part) {
                $subparts = explode(':', $part, 2);
                if (count($subparts) === 2) {
                    $key = trim($subparts[0]);
                    $val = trim($subparts[1]);
                    $key = str_replace(["\r", "\n", "\\r", "\\n"], "", $key);
                    $val = str_replace(["\r", "\n", "\\r", "\\n"], "", $val);
                    $parsedOptions[$key] = $val;
                }
            }
        }
    }

    $ratings = [
        'Bad' => ['val' => 0, 'pct' => '0%'],
        'Poor' => ['val' => $weight * 0.25, 'pct' => '25%'],
        'Average' => ['val' => $weight * 0.5, 'pct' => '50%'],
        'Good' => ['val' => $weight * 0.75, 'pct' => '75%'],
        'Excellent' => ['val' => $weight, 'pct' => '100%'],
    ];

    foreach ($ratings as $key => $info) {
        if (isset($parsedOptions[$key]) && !empty($parsedOptions[$key])) {
            $optionText = $parsedOptions[$key] . ' (' . $info['pct'] . ')';
        } else {
            $optionText = $key . ' (' . $info['pct'] . ')';
        }
        echo '<option value="' . $info['val'] . '">' . htmlspecialchars($optionText) . '</option>';
    }
}
?>

<div class="container-fluid py-4">
   
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-lg" style="border-radius: 20px; background: #f8f9fa;">
                <div class="card-header bg-primary text-white border-0 py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="bg-white rounded-circle p-2 me-3 shadow-sm">
                                <i class="fas fa-user-check text-primary h4 mb-0"></i>
                            </div>
                            <div>
                                <h5 class="text-white fw-bold mb-0">Service Provider Verification</h5>
                                <p class="mb-0 small opacity-75">Comprehensive Interview & Quality Screening</p>
                            </div>
                        </div>
                        <?php 
                            $kyc_verified = ($verifications->identity_proof_verified ?? 0) == 1;
                            $status = $service_row->status ?? '';
                            $payment_paid = $service_row->payment_paid ?? 0;
                            $call_verified = $service_row->call_verified ?? 0;
                            $is_verified = $service_row->is_verified ?? 0;

                            if ($is_verified == 1) {
                                $current_status = 'active';
                            } elseif ($call_verified == 1 && ($status == 'approved' || $status == 'rejected')) {
                                $current_status = 'approval';
                            } elseif ($payment_paid == 1 && $call_verified == 0) {
                                $current_status = 'pending_verification';
                                $created_at = $provider->created_at ?? '';
                                if ($created_at && strtotime($created_at) < strtotime('-24 hours')) {
                                    $current_status .= '&overdue=1';
                                }
                            } else {
                                $current_status = $status;
                            }
                        ?>
                        <a href="<?= base_url('providers?status=' . $current_status) ?>" class="btn btn-light rounded-pill px-4">
                            <i class="fas fa-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                </div>

                <!-- Sticky Score Banner -->
                <div class="bg-white border-bottom sticky-top py-2 shadow-premium" style="top: 0px; z-index: 1020;">
                    <div class="container-fluid px-4">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-light rounded-circle me-3 d-flex align-items-center justify-content-center" style="width:40px; height:40px;">
                                        <i class="fas fa-user text-muted"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark"><?= $provider->name ?></div>
                                        <div class="small text-muted"><?= $provider->phone ?></div>
                                        <div class="small text-muted"><?= $provider->area ?>/<?= $provider->city ?></div>


                                        <div class="d-flex align-items-center gap-1 flex-wrap mt-1">
                                            <div class="badge bg-info-soft text-info extra-small"><?= $service_name ?></div>
                                            <?php if ($service_id == 3 && !empty($selected_types)): ?>
                                                 <?php foreach ($selected_types as $type): 
                                                     $typeLabel = '';
                                                     if ($type === 'obedience_training') $typeLabel = 'Puppy & Obedience';
                                                     elseif ($type === 'behavioral_training') $typeLabel = 'Behaviour';
                                                     elseif ($type === 'advanced_training') $typeLabel = 'Advanced';
                                                     
                                                     if ($typeLabel):
                                                 ?>
                                                     <div class="badge bg-primary-soft text-primary extra-small"><?= $typeLabel ?></div>
                                                 <?php endif; endforeach; ?>
                                             <?php endif; ?>
                                             <?php if ($service_id == 2 && !empty($selected_types)): ?>
                                                 <?php foreach ($selected_types as $type): 
                                                    $typeLabel = '';
                                                    if ($type === 'grooming_and_trimming') $typeLabel = 'Grooming & Trimming';
                                                    elseif ($type === 'grooming') $typeLabel = 'Only Grooming';
                                                    elseif ($type === 'grooming_and_cut') $typeLabel = 'Grooming + Stylish Hair Cut';
                                                    
                                                    if ($typeLabel):
                                                 ?>
                                                     <div class="badge bg-primary-soft text-primary extra-small"><?= $typeLabel ?></div>
                                                 <?php endif; endforeach; ?>
                                             <?php endif; ?>
                                            <?php if ($service_row->payment_paid ?? 0): ?><span class="badge bg-success-soft text-success extra-small" style="font-size: 0.6rem;"><i class="fas fa-check-circle me-1"></i>Paid</span><?php endif; ?>
                                            <?php if ($service_row->call_verified ?? 0): ?><span class="badge bg-success-soft text-success extra-small" style="font-size: 0.6rem;"><i class="fas fa-check-circle me-1"></i>Call Verified</span><?php endif; ?>
                                            <?php if ($kyc_verified): ?><span class="badge bg-success-soft text-success extra-small" style="font-size: 0.6rem;"><i class="fas fa-id-card me-1"></i>KYC Verified</span><?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="small text-muted fw-bold mb-1">Calculated Quality Rating</div>
                                <div class="h4 text-warning mb-0" id="sv_rating_stars_display">
                                    <i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="small text-muted fw-bold mb-1">Interview Score</div>
                                <div class="h4 mb-0 fw-bold text-primary" id="sv_total_score_banner">0 <small class="text-muted">/ <?= $totalPossible ?></small></div>
                            </div>
                        </div>

                        <!-- Category Summary Bar -->
                        <div class="d-flex flex-wrap justify-content-center mt-2 pt-2 border-top extra-small text-muted">
                            <?php foreach($categories as $cat): 
                                $safe_cat_id = preg_replace('/[^a-zA-Z0-9-_]/', '_', $cat['id']);
                            ?>
                                <div class="category-summary-item mb-1" id="sum_<?= $safe_cat_id ?>" data-label="<?= $cat['label'] ?>" data-weight="<?= $cat['weight'] ?>"><?= $cat['label'] ?>: 0/<?= $cat['weight'] ?></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <form id="serviceVerificationForm" action="<?= base_url('service-verification') ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="provider_id" value="<?= $provider->id ?>">
                    <input type="hidden" name="service_id" value="<?= $service_id ?>">
                    <input type="hidden" name="rating" id="sv_rating_value" value="<?= $service_row->rating ?? 0 ?>">

                    <div class="card-body p-4 bg-light">
                        <div class="row g-4">
                            <!-- Left Column: Core Details -->
                            <div class="col-lg-4">
                                <div class="card border-0 shadow-premium rounded-4 h-100" style="background: white;">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold text-dark mb-4 d-flex align-items-center">
                                            <i class="fas fa-info-circle text-primary me-2"></i>Core Assessment
                                        </h6>
                                        <div class="mb-4">
                                            <label class="form-label fw-bold text-dark small mb-2">Update Verification Status</label>
                                            <select class="form-select border-0 bg-light rounded-3 py-2" name="status" id="sv_status" required>
                                                  <?php if ($current_status == 'approval' || $current_status == 'active'): ?>
                                                      <option value="approved" selected>Verified</option>
                                                  <?php elseif ($current_status == 'pending_verification' && $kyc_verified): ?>
                                                      <option value="approval" selected>Manager Approval (Call Verified)</option>
                                                  <?php else: ?>
                                                      <option value="pending_verification" <?= ($current_status == 'pending_verification') ? 'selected' : '' ?>>Waiting for Call Verification</option>
                                                      <?php if (!$kyc_verified): ?><option value="pending_kyc" <?= ($current_status == 'pending_kyc') ? 'selected' : '' ?>>KYC Pending</option><?php endif; ?>
                                                      <option value="approval" <?= ($current_status == 'approval') ? 'selected' : '' ?> <?= (!$kyc_verified) ? 'disabled' : '' ?>>Manager Approval (Call Verified)</option>
                                                      <option value="approved" <?= ($current_status == 'active') ? 'selected' : '' ?> <?= (!$kyc_verified) ? 'disabled' : '' ?>><?= (!$kyc_verified) ? 'Verified (KYC Pending)' : 'Verified' ?></option>
                                                  <?php endif; ?>
                                             </select>
                                        </div>
                                        <?php if (strpos($current_status, 'pending_verification') !== false): ?>
                                        <div class="mb-4 form-check">
                                            <input class="form-check-input" type="checkbox" name="not_interested" id="not_interested" value="1">
                                            <label class="form-check-label fw-bold text-danger small" for="not_interested">
                                                Provider Not Interested
                                            </label>
                                        </div>
                                        <?php endif; ?>
                                        <div class="mb-4">
                                            <label class="form-label fw-bold text-dark small mb-2">Years of Experience</label>
                                            <input type="number" class="form-control border-0 bg-light rounded-3 py-2" name="experience" value="<?= $service_details?->experience ?? '' ?>" placeholder="e.g. 5" required>
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label fw-bold text-dark small mb-2">Availability (Time)</label>
                                            <input type="text" class="form-control border-0 bg-light rounded-3 py-2" name="available_time" value="<?= $service_details?->available_time ?? '' ?>" placeholder="e.g. 8 AM - 6 PM" required>
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label fw-bold text-dark small mb-2">Confidence Level</label>
                                            <select class="form-select border-0 bg-light rounded-3 py-2" name="confidence" required>
                                                <option value="0" <?= ($service_details?->confidence ?? '') == '0' ? 'selected' : '' ?>>Low (0%)</option>
                                                <option value="25" <?= ($service_details?->confidence ?? '') == '25' ? 'selected' : '' ?>>Moderate (25%)</option>
                                                <option value="50" <?= ($service_details?->confidence ?? '50') == '50' ? 'selected' : '' ?>>Average (50%)</option>
                                                <option value="75" <?= ($service_details?->confidence ?? '') == '75' ? 'selected' : '' ?>>High (75%)</option>
                                                <option value="100" <?= ($service_details?->confidence ?? '') == '100' ? 'selected' : '' ?>>Very High (100%)</option>
                                            </select>
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label fw-bold text-dark small mb-2">Admin Interview Notes</label>
                                            <textarea class="form-control border-0 bg-light rounded-3" name="comment" rows="5" placeholder="Enter detailed notes from the call..." required><?= $service_row?->review ?? '' ?></textarea>
                                        </div>
                                        <div class="mb-0">
                                            <label class="form-label fw-bold text-dark small mb-2">Call Record (Audio File)</label>
                                            <input type="file" class="form-control border-0 bg-light" name="call_record" accept="audio/*">
                                            <?php if(!empty($service_row?->call_record)): ?>
                                                <div class="d-flex align-items-center justify-content-between mt-2 px-2 py-1 rounded-3" style="background-color: rgba(40, 167, 69, 0.1);">
                                                    <span class="text-success small fw-bold"><i class="fas fa-check-circle me-1"></i> Call Record Uploaded</span>
                                                    <span class="text-muted small text-truncate ms-2" style="max-width: 180px;" title="<?= htmlspecialchars($service_row->call_record) ?>"><?= htmlspecialchars($service_row->call_record) ?></span>
                                                </div>
                                                <audio controls class="w-100 mt-2"><source src="<?= base_url('public/uploads/providers/call_records/' . $service_row->call_record) ?>" type="audio/mpeg"></audio>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column: Interview Questions -->
                            <div class="col-lg-8">
                                <div class="accordion accordion-flush custom-accordion" id="interviewAccordion">
                                    <?php foreach($categories as $index => $cat): 
                                        $safe_cat_id = preg_replace('/[^a-zA-Z0-9-_]/', '_', $cat['id']);
                                    ?>
                                        <div class="accordion-item border-0 mb-3 shadow-sm rounded-4 overflow-hidden">
                                            <h2 class="accordion-header d-flex align-items-center bg-white pe-3">
                                                <button class="accordion-button fw-bold bg-white text-dark flex-grow-1 <?= ($index > 0) ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $safe_cat_id ?>">
                                                    <span class="category-icon bg-primary-soft text-primary me-3"><i class="fas <?= $cat['icon'] ?>"></i></span>
                                                    <?= ($index + 1) . '. ' . $cat['label'] . ' (' . $cat['weight'] . '%)' ?>
                                                </button>
                                                <!-- <a href="javascript:void(0)" class="set-satisfactory text-decoration-none fw-bold" data-target="#collapse<?= $safe_cat_id ?>">Set All Satisfactory</a> -->
                                            </h2>
                                            <div id="collapse<?= $safe_cat_id ?>" class="accordion-collapse collapse <?= ($index == 0) ? 'show' : '' ?>" data-bs-parent="#interviewAccordion">
                                                <div class="accordion-body p-4">
                                                    <div class="row">
                                                        <?php foreach($cat['questions'] as $q): ?>
                                                            <div class="<?= ($q['full_width'] ?? false) ? 'col-md-12' : 'col-md-6' ?> mb-4 <?= ($q['is_dependent']) ? 'exp-dependent d-none' : '' ?>">
                                                                 <label class="form-label fw-bold text-dark small mb-1"><?= $q['question_text'] ?> (<?= $q['weight'] ?>%)</label>
                                                                 <div class="small text-muted mb-2 lh-sm"><?= $q['hint_text'] ?></div>
                                                                 <select class="form-select border-0 bg-light q-score" 
                                                                         required 
                                                                         name="<?= !empty($q['field_name']) ? $q['field_name'] : 'q_' . $q['id'] ?>" 
                                                                         id="<?= !empty($q['field_name']) ? $q['field_name'] : 'q_' . $q['id'] ?>"
                                                                         data-trigger="<?= ($q['is_trigger']) ? 'true' : 'false' ?>" data-weight="<?= $q['weight'] ?>">
                                                                     <?php ratingOptions($q['weight'], ($service_id == 4), $q['options'] ?? ''); ?>
                                                                 </select>
                                                             </div>
                                                         <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>

                                    <!-- Mandatory Statement by Verification Executive -->
                                    <div class="alert alert-warning border-0 rounded-4 p-4 mt-4 shadow-sm" style="background: rgba(255, 193, 7, 0.12); border-left: 4px solid #ffc107 !important;">
                                        <h6 class="fw-bold d-flex align-items-center mb-2 text-dark">
                                            <span class="me-2" style="font-size: 1.25rem;">🗣️</span> Statement by Verification Executive
                                        </h6>
                                        <p class="mb-0 small fw-bold text-dark" style="font-style: italic; line-height: 1.6;">
                                            “Taking clients outside the Petsfolio platform is strictly not allowed. If you take clients offline, your account will be blocked, you must pay a penalty, and you will not receive further leads.”
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer glass-footer sticky-bottom py-3 text-end">
                        <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm fw-bold">Save Verification</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.q-score { font-size: 0.85rem !important; padding-right: 1.75rem !important; }
    .bg-primary-soft { background-color: rgba(13, 110, 253, 0.1) !important; }
    .bg-success-soft { background-color: rgba(25, 135, 84, 0.1) !important; }
    .bg-info-soft { background-color: rgba(13, 202, 240, 0.1) !important; }
    .bg-danger-soft { background-color: rgba(220, 53, 69, 0.1) !important; }
    .bg-warning-soft { background-color: rgba(255, 193, 7, 0.1) !important; }
    .text-primary { color: #0d6efd !important; }
    .category-icon { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; font-size: 0.85rem; }
    .custom-accordion .accordion-item { border: 1px solid rgba(0,0,0,0.05) !important; transition: all 0.3s ease; }
    .custom-accordion .accordion-button:not(.collapsed) { background-color: #fff !important; color: #0d6efd !important; box-shadow: none !important; }
    .custom-accordion .accordion-button:focus { box-shadow: none !important; }
    .glass-footer { background: rgba(255, 255, 255, 0.85) !important; backdrop-filter: blur(10px); border-top: 1px solid rgba(0,0,0,0.05) !important; }
    .shadow-premium { box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04) !important; }
    .category-summary-item { border-right: 1px solid rgba(0,0,0,0.05); padding: 0 15px; }
    .category-summary-item:last-child { border-right: none; }
    .extra-small { font-size: 0.7rem !important; }
    .set-satisfactory { font-size: 0.7rem; color: #adb5bd; transition: all 0.2s; }
    .set-satisfactory:hover { color: #0d6efd !important; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const interviewAnswers = <?= $service_details->interview_answers ?? 'null' ?>;
        if (interviewAnswers) {
            Object.entries(interviewAnswers).forEach(([field, val]) => {
                const element = document.getElementsByName(field)[0];
                if (element) {
                    let selectedValue = null;
                    const weight = parseFloat(element.getAttribute('data-weight') || 0);
                    if (typeof val === 'object' && val !== null) {
                        if ('answer' in val && val.answer) {
                            const answerText = val.answer;
                            let ratio = -1;
                            if (answerText.includes('100%') || answerText.toLowerCase().includes('excellent')) ratio = 1.0;
                            else if (answerText.includes('75%') || answerText.toLowerCase().includes('good')) ratio = 0.75;
                            else if (answerText.includes('50%') || answerText.toLowerCase().includes('average')) ratio = 0.5;
                            else if (answerText.includes('25%') || answerText.toLowerCase().includes('poor')) ratio = 0.25;
                            else if (answerText.includes('0%') || answerText.toLowerCase().includes('bad')) ratio = 0.0;
                            
                            if (ratio !== -1) {
                                selectedValue = ratio * weight;
                            }
                        }
                        if (selectedValue === null && 'score' in val) {
                            selectedValue = val.score;
                        }
                    } else {
                        selectedValue = val;
                    }
                    let foundMatch = false;
                    for (let i = 0; i < element.options.length; i++) {
                        if (parseFloat(element.options[i].value) === parseFloat(selectedValue)) {
                            element.value = element.options[i].value;
                            foundMatch = true;
                            break;
                        }
                    }
                    if (!foundMatch) {
                        element.value = selectedValue;
                    }
                }
            });
        }

        const calculateScore = () => {
            let total = 0;
            const totalPossible = <?= $totalPossible ?>;
            
            $('.q-score').each(function() {
                if ($(this).is(':visible') || !$(this).closest('.d-none').length) {
                    total += parseFloat($(this).val() || 0);
                }
            });

            $('#sv_total_score_banner').html(total.toFixed(1) + ' <small class="text-muted">/ ' + totalPossible + '</small>');
            
            $('.category-summary-item').each(function() {
                const catId = $(this).attr('id').replace('sum_', '');
                const targetCollapse = '#collapse' + catId;
                let catScore = 0;
                $(targetCollapse).find('.q-score').each(function() {
                     if ($(this).is(':visible') || !$(this).closest('.d-none').length) {
                        catScore += parseFloat($(this).val() || 0);
                     }
                });
                const catWeight = $(this).data('weight');
                const catLabel = $(this).data('label');
                $(this).text(catLabel + ': ' + catScore.toFixed(1) + '/' + catWeight);
            });

            let finalRating = 0;
            let percentage = (total / totalPossible) * 100;
            if (percentage >= 90) finalRating = 5;
            else if (percentage >= 75) finalRating = 4;
            else if (percentage >= 55) finalRating = 3;
            else if (percentage >= 35) finalRating = 2;
            else if (total > 0) finalRating = 1;

            if ($('#not_interested').is(':checked')) {
                finalRating = 1;
            }

            $('#sv_rating_value').val(finalRating);
            let starsHtml = '';
            for (let i = 1; i <= 5; i++) {
                starsHtml += i <= finalRating ? '<i class="fas fa-star text-warning"></i>' : '<i class="far fa-star text-muted"></i>';
            }
            $('#sv_rating_stars_display').html(starsHtml);
        };

        const toggleExpDependent = () => {
            const triggerEl = $('select[data-trigger="true"]');
            if (triggerEl.length) {
                const hasExp = parseFloat(triggerEl.val() || 0) > 0;
                if (hasExp) {
                    $('.exp-dependent').removeClass('d-none');
                } else {
                    $('.exp-dependent').addClass('d-none').find('select').val(0);
                }
            }
            calculateScore();
        };

        $(document).on('change', '.q-score', function() {
            if ($(this).data('trigger') === true) toggleExpDependent();
            else calculateScore();
        });

        $(document).on('change', '#not_interested', function() {
            if ($(this).is(':checked')) {
                $('.q-score').removeAttr('required').val(0).trigger('change');
                $('input[name="experience"], input[name="available_time"], select[name="confidence"], textarea[name="comment"]').removeAttr('required');
                $('#sv_status').find('option[value="approval"]').removeAttr('disabled');
                $('#sv_status').val('approval');
            } else {
                $('.q-score').attr('required', true);
                $('input[name="experience"], input[name="available_time"], select[name="confidence"], textarea[name="comment"]').attr('required', true);
            }
            calculateScore();
        });

        $(document).on('click', '.set-satisfactory', function(e) {
            e.preventDefault();
            const target = $(this).data('target');
            $(target).find('.q-score').each(function() {
                if ($(this).closest('.exp-dependent').length && parseFloat($('select[data-trigger="true"]').val() || 0) === 0) return;
                let maxVal = 0;
                $(this).find('option').each(function() {
                    let v = parseFloat($(this).val());
                    if (v > maxVal) maxVal = v;
                });
                $(this).val(maxVal);
            });
            toggleExpDependent();
        });

        toggleExpDependent();
        calculateScore();
    });
</script>
<?= $this->endSection(); ?>
