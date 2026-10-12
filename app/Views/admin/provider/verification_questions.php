<?php echo $this->extend('admin/includes/header'); ?>

<?php echo $this->section('content'); ?>
<div class="conatiner-fluid content-inner pb-0 px-4" id="page_layout">
  <!-- Modern Header Section -->
  <div class="container-fluid p-0 mb-4 bg-gradient-info rounded-3">
    <div class=" text-white p-5 shadow position-relative overflow-hidden">
      <div class="position-relative z-1">
        <h1 class="h2 fw-bold mb-2 text-white">Interview Questions</h1>
        <p class="text-indigo-100 mb-0">Manage quality assessment questions for service provider verification</p>
      </div>
      <i class="bi bi-question-circle position-absolute end-0 bottom-0 opacity-10"
        style="font-size: 12rem; transform: rotate(12deg); margin-right: -2rem; margin-bottom: -2rem;"></i>
    </div>
    <div class="p-3">
      <div class="card border-0 shadow-sm mb-0">
        <div class="card-body p-3">
          <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-3 w-100 w-md-auto">
              <div class="input-group flex-grow-1 flex-md-grow-0">
                <span class="input-group-text bg-light border-end-0">
                  <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" class="form-control border-start-0 ps-0" placeholder="Search questions..." id="questionSearch">
              </div>
              <button type="button" class="btn bg-primary text-white d-flex align-items-center justify-content-center gap-1" onclick="openAddQuestionModal()">
                <i class="bi bi-plus-lg"></i>
                <span>Add New Question</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Service Tabs -->
  <ul class="nav nav-tabs nav-fill mb-4 bg-white rounded-3 shadow-sm p-2 border-0" id="serviceTabs" role="tablist">
<<<<<<< HEAD
    <?php 

    foreach ($services as $index => $service): ?>
      <li class="nav-item" role="presentation">
        <button class="nav-link <?= $index === 0 ? 'active' : '' ?> rounded-3 fw-bold py-3" 
                id="service-tab-<?= $service['id'] ?>" 
                data-bs-toggle="tab" 
                data-bs-target="#service-pane-<?= $service['id'] ?>" 
                data-toggle="tab"
                data-target="#service-pane-<?= $service['id'] ?>"
                type="button" role="tab">
=======
    <?php

    foreach ($services as $index => $service): ?>
      <li class="nav-item" role="presentation">
        <button class="nav-link <?= $index === 0 ? 'active' : '' ?> rounded-3 fw-bold py-3"
          id="service-tab-<?= $service['id'] ?>"
          data-bs-toggle="tab"
          data-bs-target="#service-pane-<?= $service['id'] ?>"
          data-toggle="tab"
          data-target="#service-pane-<?= $service['id'] ?>"
          type="button" role="tab">
>>>>>>> origin/main
          <i class="<?= $service['id'] == 4 ? 'bi bi-person-walking' : ($service['id'] == 2 ? 'bi bi-scissors' : 'bi bi-mortarboard') ?> me-2"></i>
          <?= $service['name'] ?>
        </button>
      </li>
    <?php endforeach; ?>
  </ul>

  <div class="tab-content" id="serviceTabsContent">
    <?php foreach ($services as $index => $service): ?>
<<<<<<< HEAD
      <div class="tab-pane fade <?= $index === 0 ? 'show active' : '' ?>" 
           id="service-pane-<?= $service['id'] ?>" 
           role="tabpanel">
        
=======
      <div class="tab-pane fade <?= $index === 0 ? 'show active' : '' ?>"
        id="service-pane-<?= $service['id'] ?>"
        role="tabpanel">

>>>>>>> origin/main
        <?php if (isset($groupedQuestions[$service['id']])): ?>
          <?php foreach ($groupedQuestions[$service['id']] as $categoryName => $catQuestions): ?>
            <div class="card border-0 shadow-sm mb-4">
              <div class="card-header bg-light border-0 py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                  <span class="badge bg-primary rounded-circle p-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-tag-fill text-white"></i>
                  </span>
                  <div>
                    <h5 class="mb-0 fw-bold"><?= $categoryName ?></h5>
                    <small class="text-muted">Weight: <?= (float)$catQuestions[0]['category_weight'] ?>% of total score</small>
                  </div>
                </div>
                <span class="badge bg-white text-primary border border-primary border-opacity-25 px-3 py-2">
                  <?= count($catQuestions) ?> Questions
                </span>
              </div>
              <div class="table-responsive">
                <table class="table table-hover mb-0">
                  <thead class="bg-light">
                    <tr>
                      <th class="ps-4 py-3 text-muted fs-7 text-uppercase fw-bold border-bottom">Question</th>
                      <th class="py-3 text-muted fs-7 text-uppercase fw-bold border-bottom text-center">Weight</th>
                      <th class="py-3 text-muted fs-7 text-uppercase fw-bold border-bottom text-center">Flags</th>
                      <th class="py-3 text-muted fs-7 text-uppercase fw-bold border-bottom text-center">Status</th>
                      <th class="pe-4 py-3 text-muted fs-7 text-uppercase fw-bold border-bottom text-end">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($catQuestions as $q): ?>
                      <tr>
                        <td class="ps-4 py-3">
                          <div class="text-wrap" style="max-width: 500px;">
                            <div class="fw-bold text-dark fs-6"><?= $q['question_text'] ?></div>
                            <small class="text-muted d-block mt-1">
                              <i class="bi bi-info-circle me-1"></i>Hint: <?= $q['hint_text'] ?>
                            </small>
                            <?php if ($q['field_name']): ?>
                              <code class="small bg-light px-2 py-1 rounded mt-2 d-inline-block text-primary">Map: <?= $q['field_name'] ?></code>
                            <?php endif; ?>
                          </div>
                        </td>
                        <td class="py-3 text-center">
                          <div class="d-flex flex-column align-items-center">
                            <span class="fw-bold text-primary"><?= (float)$q['weight'] ?>%</span>
                            <small class="text-muted tiny">score weight</small>
                          </div>
                        </td>
                        <td class="py-3 text-center">
<<<<<<< HEAD
                          <div class="d-flex gap-1 justify-content-center flex-wrap">
=======
                          <div class="d-flex gap-1 justify-content-center">
>>>>>>> origin/main
                            <?php if ($q['is_trigger']): ?>
                              <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25" title="Experience Trigger">Trigger</span>
                            <?php endif; ?>
                            <?php if ($q['is_dependent']): ?>
                              <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25" title="Dependent Question">Dep</span>
                            <?php endif; ?>
<<<<<<< HEAD
                            <?php if (!empty($q['training_type'])): ?>
                              <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25" title="Training Type: <?= htmlspecialchars($q['training_type']) ?>">
                                <?= ucfirst(str_replace('_', ' ', $q['training_type'])) ?>
                              </span>
                            <?php endif; ?>
=======
>>>>>>> origin/main
                          </div>
                        </td>
                        <td class="py-3 text-center">
                          <span class="badge <?= $q['status'] === 'active' ? 'bg-success' : 'bg-danger' ?> bg-opacity-10 <?= $q['status'] === 'active' ? 'text-success' : 'text-danger' ?> border px-3 py-1 fw-medium" style="border-radius: 50px;">
                            <?= ucfirst($q['status']) ?>
                          </span>
                        </td>
                        <td class="pe-4 py-3 text-end">
                          <div class="d-flex justify-content-end gap-2">
                            <button class="btn btn-sm btn-icon btn-outline-primary border-0 bg-light" onclick="editQuestion(<?= $q['id'] ?>)" title="Edit">
                              <i class="bi bi-pencil-square"></i>
                            </button>
                            <button class="btn btn-sm btn-icon btn-outline-danger border-0 bg-light" onclick="deleteQuestion(<?= $q['id'] ?>)" title="Delete">
                              <i class="bi bi-trash"></i>
                            </button>
                          </div>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="card border-0 shadow-sm p-5 text-center">
            <div class="mb-3 text-muted">
              <i class="bi bi-clipboard-x" style="font-size: 4rem;"></i>
            </div>
            <h4>No questions found for this service</h4>
            <p class="text-muted">Start by adding a new question for <?= $service['name'] ?>.</p>
            <button class="btn btn-primary w-auto mx-auto px-4" onclick="openAddQuestionModal(<?= $service['id'] ?>)">
              <i class="bi bi-plus-lg me-2"></i>Add First Question
            </button>
          </div>
        <?php endif; ?>

      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Question Admin Modal -->
<<<<<<< HEAD
<div id="question-admin-container" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm hidden">
    <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden border border-gray-100 transform transition-all duration-300 scale-95 opacity-0" id="question-admin-modal">
        <div class="p-6 border-bottom bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="h5 mb-0 fw-bold" id="modal-title">Add Interview Question</h3>
                <button type="button" class="btn-close" onclick="closeQuestionAdminModal()"></button>
            </div>
        </div>
        <form id="question-form" class="p-6">
            <input type="hidden" name="id" id="question-id">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-muted text-uppercase">Service</label>
                    <select name="service_id" id="q-service-id" class="form-select border-light bg-light" required>
                        <?php foreach($services as $service): ?>
                            <option value="<?= $service['id'] ?>"><?= $service['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-muted text-uppercase">Sort Order</label>
                    <input type="number" name="sort_order" id="q-sort-order" class="form-control border-light bg-light" value="0">
                </div>
                <div class="col-md-8">
                    <label class="form-label small fw-bold text-muted text-uppercase">Category Name</label>
                    <input type="text" name="category_name" id="q-category-name" class="form-control border-light bg-light" placeholder="e.g. Behaviour" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted text-uppercase">Cat. Weight (%)</label>
                    <input type="number" step="0.1" name="category_weight" id="q-category-weight" class="form-control border-light bg-light" placeholder="30" required>
                </div>
                <div class="col-md-12">
                    <label class="form-label small fw-bold text-muted text-uppercase">Question Text</label>
                    <textarea name="question_text" id="q-text" class="form-control border-light bg-light" rows="2" placeholder="Enter the question..." required></textarea>
                </div>
                <div class="col-md-12">
                    <label class="form-label small fw-bold text-muted text-uppercase">Hint / Expected Answer</label>
                    <textarea name="hint_text" id="q-hint" class="form-control border-light bg-light" rows="2" placeholder="What should the provider say?"></textarea>
                </div>
                <div class="col-md-12">
                    <label class="form-label small fw-bold text-muted text-uppercase">Options (for dropdown - comma separated)</label>
                    <input type="text" name="options" id="q-options" class="form-control border-light bg-light" placeholder="e.g. Bad,Poor,Average,Good,Excellent">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted text-uppercase">Question Weight (%)</label>
                    <input type="number" step="0.1" name="weight" id="q-weight" class="form-control border-light bg-light" placeholder="5" required>
                </div>
                <div class="col-md-8 d-flex align-items-center mt-4">
                    <div class="form-check form-switch me-3">
                        <input class="form-check-input" type="checkbox" name="is_trigger" id="q-is-trigger">
                        <label class="form-check-label fw-medium small">Exp. Trigger</label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_dependent" id="q-is-dependent">
                        <label class="form-check-label fw-medium small">Dependent</label>
                    </div>
                </div>
                <div class="col-md-12">
                    <label class="form-label small fw-bold text-muted text-uppercase">Field Name (Optional - for legacy support)</label>
                    <input type="text" name="field_name" id="q-field-name" class="form-control border-light bg-light" placeholder="e.g. q_why_work_with_pets">
                    <small class="text-muted" style="font-size: 0.7rem;">Leave empty to use dynamic ID (q_123)</small>
                </div>
                <div class="col-md-12">
                    <label class="form-label small fw-bold text-muted text-uppercase">Specialization Type (For Training & Grooming)</label>
                    <select name="training_type" id="q-training-type" class="form-select border-light bg-light">
                        <option value="">General / None</option>
                        <option value="obedience_training">Puppy & Obedience Training</option>
                        <option value="behavioral_training">Behaviour Training</option>
                        <option value="advanced_training">Advanced Training</option>
                        <option value="grooming_and_trimming">Grooming & Trimming</option>
                        <option value="grooming">Only Grooming</option>
                        <option value="grooming_and_cut">Grooming + Stylish Hair Cut</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label small fw-bold text-muted text-uppercase">Status</label>
                    <select name="status" id="q-status" class="form-select border-light bg-light">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary w-100 py-3 rounded-2xl fw-bold shadow-sm">Save Question</button>
            </div>
        </form>
    </div>
=======
<div id="question-admin-container" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm hidden" style="z-index: 9999;">
  <div class="bg-white rounded-3xl shadow-2xl max-w-xl w-full overflow-hidden border border-gray-100 transform transition-all duration-300 scale-95 opacity-0" id="question-admin-modal">
    <!-- Modal Header with Gradient -->
    <div class="modal-gradient-header p-4 text-white position-relative">
      <div class="d-flex justify-content-between align-items-center position-relative z-1">
        <div class="d-flex align-items-center gap-3">
          <div class="bg-white bg-opacity-20 p-2 rounded-2xl">
            <i class="bi bi-pencil-square fs-4"></i>
          </div>
          <div>
            <h3 class="h5 mb-0 fw-bold" id="modal-title">Add Interview Question</h3>
            <p class="small mb-0 opacity-75">Configure question details and weights</p>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white" onclick="closeQuestionAdminModal()"></button>
      </div>
      <!-- Decorative circle -->
      <div class="position-absolute end-0 top-0 translate-middle-y mt-4 me-n4 bg-white bg-opacity-10 rounded-circle" style="width: 100px; height: 100px;"></div>
    </div>

    <form id="question-form" class="mb-0">
      <input type="hidden" name="id" id="question-id">

      <!-- Scrollable Body Area -->
      <div class="modal-body-scroll p-4 p-md-5" style="max-height: 60vh; overflow-y: auto;">
        <div class="row g-4">
          <!-- Section: Basic Info -->
          <div class="col-12 mt-0">
            <div class="d-flex align-items-center gap-2 mb-3">
              <span class="badge bg-primary-subtle text-primary rounded-circle p-1">1</span>
              <h6 class="mb-0 fw-bold text-dark">Basic Information</h6>
            </div>
          </div>

          <div class="col-md-6">
            <label class="form-label small fw-bold text-muted text-uppercase mb-1">Service Association</label>
            <div class="input-group-modern">
              <span class="input-icon"><i class="bi bi-layers"></i></span>
              <select name="service_id" id="q-service-id" class="form-select modern-input" required>
                <?php foreach ($services as $service): ?>
                  <option value="<?= $service['id'] ?>"><?= $service['name'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="col-md-6">
            <label class="form-label small fw-bold text-muted text-uppercase mb-1">Display Sort Order</label>
            <div class="input-group-modern">
              <span class="input-icon"><i class="bi bi-sort-numeric-down"></i></span>
              <input type="number" name="sort_order" id="q-sort-order" class="form-control modern-input" value="0">
            </div>
          </div>

          <div class="col-md-8">
            <label class="form-label small fw-bold text-muted text-uppercase mb-1">Category Name</label>
            <div class="input-group-modern">
              <span class="input-icon"><i class="bi bi-tag"></i></span>
              <input type="text" name="category_name" id="q-category-name" class="form-control modern-input" placeholder="e.g. Behavioural Assessment" required>
            </div>
          </div>

          <div class="col-md-4">
            <label class="form-label small fw-bold text-muted text-uppercase mb-1">Cat. Weight (%)</label>
            <div class="input-group-modern">
              <span class="input-icon"><i class="bi bi-percent"></i></span>
              <input type="number" step="0.1" name="category_weight" id="q-category-weight" class="form-control modern-input" placeholder="30.0" required>
            </div>
          </div>

          <!-- Section: Question Content -->
          <div class="col-12 mt-5">
            <div class="d-flex align-items-center gap-2 mb-3">
              <span class="badge bg-primary-subtle text-primary rounded-circle p-1">2</span>
              <h6 class="mb-0 fw-bold text-dark">Question Content & Options</h6>
            </div>
          </div>

          <div class="col-md-12">
            <label class="form-label small fw-bold text-muted text-uppercase mb-1">Question Text</label>
            <textarea name="question_text" id="q-text" class="form-control modern-input" rows="3" placeholder="Type your question here..." required></textarea>
          </div>

          <div class="col-md-12">
            <label class="form-label small fw-bold text-muted text-uppercase mb-1">Hint / Expected Answer</label>
            <textarea name="hint_text" id="q-hint" class="form-control modern-input" rows="2" placeholder="Briefly describe what a good answer looks like..."></textarea>
          </div>

          <div class="col-md-12">
            <label class="form-label small fw-bold text-muted text-uppercase mb-1">Dropdown Options (Comma Separated)</label>
            <div class="input-group-modern">
              <span class="input-icon"><i class="bi bi-list-check"></i></span>
              <input type="text" name="options" id="q-options" class="form-control modern-input" placeholder="Excellent, Good, Fair, Poor">
            </div>
          </div>

          <!-- Section: Scoring & Logic -->
          <div class="col-12 mt-5">
            <div class="d-flex align-items-center gap-2 mb-3">
              <span class="badge bg-primary-subtle text-primary rounded-circle p-1">3</span>
              <h6 class="mb-0 fw-bold text-dark">Scoring & Logic</h6>
            </div>
          </div>

          <div class="col-md-12">
            <label class="form-label small fw-bold text-muted text-uppercase mb-1">Question Weight (%)</label>
            <div class="input-group-modern">
              <span class="input-icon"><i class="bi bi-calculator"></i></span>
              <input type="number" step="0.1" name="weight" id="q-weight" class="form-control modern-input" placeholder="5.0" required>
            </div>
          </div>

          <div class="col-md-12">
            <div class="row g-3">
              <div class="col-6">
                <div class="form-check form-switch custom-switch p-3 border rounded-3 bg-light bg-opacity-50 transition-all w-100">
                  <input class="form-check-input ms-0 me-2" type="checkbox" name="is_trigger" id="q-is-trigger">
                  <label class="form-check-label fw-bold text-dark small cursor-pointer" for="q-is-trigger">Exp. Trigger</label>
                </div>
              </div>
              <div class="col-6">
                <div class="form-check form-switch custom-switch p-3 border rounded-3 bg-light bg-opacity-50 transition-all w-100">
                  <input class="form-check-input ms-0 me-2" type="checkbox" name="is_dependent" id="q-is-dependent">
                  <label class="form-check-label fw-bold text-dark small cursor-pointer" for="q-is-dependent">Dependent</label>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-12">
            <label class="form-label small fw-bold text-muted text-uppercase mb-1">System Field Name (Optional)</label>
            <div class="input-group-modern">
              <span class="input-icon"><i class="bi bi-code-slash"></i></span>
              <input type="text" name="field_name" id="q-field-name" class="form-control modern-input" placeholder="e.g. q_pet_experience">
            </div>
          </div>

          <div class="col-md-12">
            <label class="form-label small fw-bold text-muted text-uppercase mb-1">Question Status</label>
            <div class="input-group-modern">
              <span class="input-icon"><i class="bi bi-toggle-on"></i></span>
              <select name="status" id="q-status" class="form-select modern-input">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <!-- Fixed Footer with Actions -->
      <div class="p-4 bg-light border-top d-flex gap-3">
        <button type="button" class="btn btn-light px-4 py-3 rounded-2xl fw-bold text-muted flex-fill border" onclick="closeQuestionAdminModal()">Cancel</button>
        <button type="submit" class="btn btn-primary px-4 py-3 rounded-2xl fw-bold shadow-lg flex-fill" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); border: none;">
          Save Question
        </button>
      </div>
    </form>
  </div>
>>>>>>> origin/main
</div>

<style>
  .bg-gradient-info {
<<<<<<< HEAD
    background: linear-gradient(135deg, #0dcaf0 0%, #0aa2c0 100%) !important;
  }
  .bg-black\/60 { background-color: rgba(0, 0, 0, 0.6); }
  .backdrop-blur-sm { backdrop-filter: blur(4px); }
  .rounded-3xl { border-radius: 1.5rem; }
  .rounded-2xl { border-radius: 1rem; }
  .shadow-2xl { box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); }
  .fixed { position: fixed; }
  .inset-0 { top: 0; right: 0; bottom: 0; left: 0; }
  .z-50 { z-index: 50; }
  .hidden { display: none; }
=======
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%) !important;
  }

  .bg-primary-subtle {
    background-color: rgba(79, 70, 229, 0.1);
  }

  .bg-black\/60 {
    background-color: rgba(15, 23, 42, 0.7);
  }

  .backdrop-blur-sm {
    backdrop-filter: blur(8px);
  }

  .rounded-3xl {
    border-radius: 2rem;
  }

  .rounded-2xl {
    border-radius: 1.25rem;
  }

  .shadow-2xl {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
  }

  .fixed {
    position: fixed;
  }

  .inset-0 {
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
  }

  .z-50 {
    z-index: 50;
  }

  .hidden {
    display: none;
  }

  .cursor-pointer {
    cursor: pointer;
  }

  .tiny {
    font-size: 0.7rem;
  }

  .modal-gradient-header {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  }

  /* Modern Input Styling */
  .input-group-modern {
    position: relative;
    display: flex;
    align-items: center;
  }

  .input-icon {
    position: absolute;
    left: 1rem;
    color: #94a3b8;
    z-index: 10;
    font-size: 1.1rem;
    pointer-events: none;
  }

  .modern-input {
    border-radius: 1rem !important;
    padding: 0.75rem 1rem 0.75rem 2.75rem !important;
    border: 2px solid #f1f5f9 !important;
    background-color: #f8fafc !important;
    font-size: 0.95rem;
    transition: all 0.2s ease;
    color: #1e293b;
  }

  textarea.modern-input {
    padding-left: 1rem !important;
    /* No icon for textareas typically */
  }

  .modern-input:focus {
    background-color: #ffffff !important;
    border-color: #6366f1 !important;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1) !important;
    outline: none;
  }

  /* Custom Switch Styling */
  .custom-switch {
    display: flex;
    align-items: center;
    margin-bottom: 0 !important;
  }

  .custom-switch:hover {
    background-color: rgba(99, 102, 241, 0.05) !important;
    border-color: rgba(99, 102, 241, 0.2) !important;
  }

  .custom-switch .form-check-input {
    width: 2.5rem;
    height: 1.25rem;
    margin-top: 0;
    cursor: pointer;
  }

  .custom-switch .form-check-input:checked {
    background-color: #6366f1;
    border-color: #6366f1;
  }

  .transition-all {
    transition: all 0.3s ease;
  }

  /* Custom Scrollbar */
  .modal-body-scroll::-webkit-scrollbar {
    width: 6px;
  }

  .modal-body-scroll::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
  }

  .modal-body-scroll::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
  }

  .modal-body-scroll::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
  }
>>>>>>> origin/main
</style>

<script>
  function openAddQuestionModal(serviceId = null) {
    $('#question-form')[0].reset();
    $('#question-id').val('');
    $('#q-options').val('');
<<<<<<< HEAD
    $('#q-training-type').val('');
    if (serviceId) {
        $('#q-service-id').val(serviceId);
=======
    if (serviceId) {
      $('#q-service-id').val(serviceId);
>>>>>>> origin/main
    }
    $('#modal-title').text('Add Interview Question');
    showModal();
  }

  function editQuestion(id) {
    $.get('<?= base_url('verification-questions/get-data/') ?>' + id, function(res) {
<<<<<<< HEAD
        $('#question-id').val(res.id);
        $('#q-service-id').val(res.service_id);
        $('#q-category-name').val(res.category_name);
        $('#q-category-weight').val(res.category_weight);
        $('#q-text').val(res.question_text);
        $('#q-hint').val(res.hint_text);
        $('#q-options').val(res.options);
        $('#q-weight').val(res.weight);
        $('#q-sort-order').val(res.sort_order);
        $('#q-field-name').val(res.field_name);
        $('#q-training-type').val(res.training_type || '');
        $('#q-is-trigger').prop('checked', res.is_trigger == 1);
        $('#q-is-dependent').prop('checked', res.is_dependent == 1);
        $('#q-status').val(res.status);
        
        $('#modal-title').text('Edit Interview Question');
        showModal();
=======
      $('#question-id').val(res.id);
      $('#q-service-id').val(res.service_id);
      $('#q-category-name').val(res.category_name);
      $('#q-category-weight').val(res.category_weight);
      $('#q-text').val(res.question_text);
      $('#q-hint').val(res.hint_text);
      $('#q-options').val(res.options);
      $('#q-weight').val(res.weight);
      $('#q-sort-order').val(res.sort_order);
      $('#q-field-name').val(res.field_name);
      $('#q-is-trigger').prop('checked', res.is_trigger == 1);
      $('#q-is-dependent').prop('checked', res.is_dependent == 1);
      $('#q-status').val(res.status);

      $('#modal-title').text('Edit Interview Question');
      showModal();
>>>>>>> origin/main
    });
  }

  function deleteQuestion(id) {
    if (confirm('Are you sure you want to delete this question?')) {
      window.location.href = '<?= base_url('verification-questions/delete/') ?>' + id;
    }
  }

  function showModal() {
    $('#question-admin-container').removeClass('hidden');
    setTimeout(() => {
      $('#question-admin-modal').removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100');
    }, 10);
  }

  function closeQuestionAdminModal() {
    $('#question-admin-modal').removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
    setTimeout(() => {
      $('#question-admin-container').addClass('hidden');
    }, 300);
  }

  $(document).ready(function() {
    $('#question-form').on('submit', function(e) {
      e.preventDefault();
      $.post('<?= base_url('verification-questions/save') ?>', $(this).serialize(), function(res) {
<<<<<<< HEAD
          if (res.status == 'success') {
              location.reload();
          } else {
              alert('Error saving question');
          }
=======
        if (res.status == 'success') {
          location.reload();
        } else {
          alert('Error saving question');
        }
>>>>>>> origin/main
      });
    });

    $('#questionSearch').on('keyup', function() {
      const value = $(this).val().toLowerCase();
      $('.tab-pane.active table tbody tr').filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
    });
  });
</script>

<<<<<<< HEAD
<?php echo $this->endSection() ?>
=======
<?php echo $this->endSection() ?>
>>>>>>> origin/main
