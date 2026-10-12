<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Models\VerificationQuestionModel;
use App\Models\ServiceModel;

class VerificationQuestionController extends BaseController
{
    protected $questionModel;
    protected $serviceModel;

    public function __construct()
    {
        $this->questionModel = new VerificationQuestionModel();
        $this->serviceModel = new ServiceModel();
        helper('Common_helper');
    }

    public function index()
    {
        $db = \Config\Database::connect();
<<<<<<< HEAD
        if (!$db->tableExists('verification_questions') || $db->table('verification_questions')->countAll() < 100) {
=======
        if (!$db->tableExists('verification_questions') || $db->table('verification_questions')->countAll() < 90) {
>>>>>>> origin/main
            $this->runInitialSetup();
        }

        $data['title'] = 'Verification Questions';
        $data['page']  = 'verification-questions';
<<<<<<< HEAD

        $services = $this->serviceModel->where('status', 'Active')->findAll();
        $questions = $this->questionModel->orderBy('sort_order', 'ASC')->findAll();

=======
        
        $services = $this->serviceModel->where('status', 'Active')->get()->getResultArray();
        $questions = $this->questionModel->orderBy('sort_order', 'ASC')->get()->getResultArray();
        
>>>>>>> origin/main
        $groupedQuestions = [];
        foreach ($questions as $q) {
            $groupedQuestions[$q['service_id']][$q['category_name']][] = $q;
        }

        $data['services'] = $services;
        $data['groupedQuestions'] = $groupedQuestions;
<<<<<<< HEAD

=======
        
>>>>>>> origin/main
        return view('admin/provider/verification_questions', $data);
    }

    public function save()
    {
        $id = $this->request->getPost('id');
        $data = [
            'service_id'      => $this->request->getPost('service_id'),
            'category_name'   => $this->request->getPost('category_name'),
            'category_weight' => $this->request->getPost('category_weight'),
            'question_text'   => $this->request->getPost('question_text'),
            'hint_text'       => $this->request->getPost('hint_text'),
            'options'         => $this->request->getPost('options'),
            'weight'          => $this->request->getPost('weight'),
            'is_trigger'      => $this->request->getPost('is_trigger') ? 1 : 0,
            'is_dependent'    => $this->request->getPost('is_dependent') ? 1 : 0,
            'sort_order'      => $this->request->getPost('sort_order') ?? 0,
            'status'          => $this->request->getPost('status') ?? 'active',
            'field_name'      => $this->request->getPost('field_name'),
            'training_type'   => $this->request->getPost('training_type'),
<<<<<<< HEAD
=======
            'group_key'       => $this->request->getPost('group_key'),
>>>>>>> origin/main
        ];

        if ($id) {
            $this->questionModel->update($id, $data);
            $msg = 'Question updated successfully';
        } else {
            $this->questionModel->insert($data);
            $msg = 'Question added successfully';
        }

        return $this->response->setJSON(['status' => 'success', 'message' => $msg]);
    }

    public function getData($id)
    {
        $data = $this->questionModel->find($id);
        return $this->response->setJSON($data);
    }

    public function delete($id)
    {
        $this->questionModel->delete($id);
        return redirect()->back()->with('success', 'Question deleted successfully');
    }

    private function runInitialSetup()
    {
        set_time_limit(300);
        $db = \Config\Database::connect();
        $forge = \Config\Database::forge();

<<<<<<< HEAD
        // Create table if not exists with options and training_type columns
=======
        // Create table if not exists with options column
>>>>>>> origin/main
        $fields = [
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'service_id' => ['type' => 'INT', 'constraint' => 11],
            'category_name' => ['type' => 'VARCHAR', 'constraint' => 255],
            'category_weight' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0.00],
            'question_text' => ['type' => 'TEXT'],
            'hint_text' => ['type' => 'TEXT', 'null' => true],
<<<<<<< HEAD
            'options' => ['type' => 'TEXT', 'null' => true],
=======
            'options' => ['type' => 'TEXT', 'null' => true], // New column for dropdowns
>>>>>>> origin/main
            'weight' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0.00],
            'is_trigger' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'is_dependent' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'sort_order' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'status' => ['type' => 'ENUM', 'constraint' => ['active', 'inactive'], 'default' => 'active'],
            'field_name' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
<<<<<<< HEAD
            'training_type' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
=======
>>>>>>> origin/main
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ];

        $forge->addField($fields);
        $forge->addKey('id', true);
        $forge->createTable('verification_questions', true);

        // Check if options column exists (for existing tables)
        if (!$db->fieldExists('options', 'verification_questions')) {
            $forge->addColumn('verification_questions', [
                'options' => ['type' => 'TEXT', 'null' => true, 'after' => 'hint_text']
            ]);
        }

<<<<<<< HEAD
        // Check if training_type column exists
        if (!$db->fieldExists('training_type', 'verification_questions')) {
            $forge->addColumn('verification_questions', [
                'training_type' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true, 'after' => 'field_name']
            ]);
        }

        // Seed if empty or incomplete (threshold of 160)
        if ($db->table('verification_questions')->countAll() < 160) {
            $db->table('verification_questions')->truncate();
            $seedData = [];
            $commonOptions = "Bad,Poor,Average,Good,Excellent";

=======
        // Seed if empty or incomplete (Increased threshold to 90 to ensure full list)
        if ($db->table('verification_questions')->countAll() < 90) {
            $db->table('verification_questions')->truncate();
            $seedData = [];
            $commonOptions = "Bad,Poor,Average,Good,Excellent";
            
>>>>>>> origin/main
            // --- DOG WALKING (ID 4) ---
            $walking = [
                ['Behaviour, Psychology & Pet Handling', 30, "TEST-v2: Why did you choose to work with pets?", "I genuinely love animals, feel comfortable around dogs, and enjoy taking care of them.", 2, 0, 0, 'q_why_work_with_pets'],
                ['Behaviour, Psychology & Pet Handling', 30, "If a dog tries to bite or misbehaves, what will you do?", "I will stay calm, avoid shouting or hitting, give the dog space, and try to control the situation safely.", 5, 1, 0, 'q_handle_bite'],
                ['Behaviour, Psychology & Pet Handling', 30, "How do you handle a scared dog?", "I will approach slowly, speak gently, avoid forcing interaction, and give the dog time to trust me.", 4, 0, 0, 'q_handle_scared'],
                ['Behaviour, Psychology & Pet Handling', 30, "What signs show you that a dog is stressed or uncomfortable?", "Tail tucked, avoiding eye contact, lip licking, growling, moving away, nervous behaviour.", 5, 0, 0, 'q_stress_signs'],
                ['Behaviour, Psychology & Pet Handling', 30, "What basic precautions do you take before starting service with a new dog?", "I observe the dog’s behaviour, check leash and surroundings, and make the dog comfortable first.", 4, 0, 0, 'q_precautions'],
                ['Behaviour, Psychology & Pet Handling', 30, "What should you never do while handling a dog?", "Never shout, hit, scare, pull harshly, or force the dog.", 3, 0, 0, 'q_never_do'],
                ['Behaviour, Psychology & Pet Handling', 30, "How do you make a new dog comfortable in the first few minutes?", "I give the dog space, let it smell me, speak softly, and avoid sudden actions.", 3, 0, 0, 'q_make_comfortable'],
                ['Behaviour, Psychology & Pet Handling', 30, "Can you share one difficult situation you faced with a dog and how you handled it?", "Real situation handled calmly, patiently, and safely without aggression.", 4, 0, 0, 'q_difficult_situation'],
<<<<<<< HEAD

                ['Experience', 20, "Do you have experience handling dogs?", "Yes, I have practical experience handling dogs.", 8, 1, 0, 'q_has_exp'],
                ['Experience', 20, "How many years of experience do you have with dogs?", "Clear hands-on experience explanation with confidence.", 7, 0, 1, 'q_years_exp'],
                ['Experience', 20, "Do you own or have you owned a pet?", "Yes, I understand pet behaviour and responsibilities.", 5, 0, 0, 'q_own_pet'],

                ['Job Commitment', 15, "Is this full-time or part-time for you? How serious are you about this work?", "I am serious about this work and can commit consistently.", 8, 0, 0, 'q_commitment'],
                ['Job Commitment', 15, "Do you agree to follow Petsfolio rules and not take clients offline?", "Yes, I will strictly follow Petsfolio policies.", 7, 0, 0, 'q_follow_rules'],

=======
                
                ['Experience', 20, "Do you have experience handling dogs?", "Yes, I have practical experience handling dogs.", 8, 1, 0, 'q_has_exp'],
                ['Experience', 20, "How many years of experience do you have with dogs?", "Clear hands-on experience explanation with confidence.", 7, 0, 1, 'q_years_exp'],
                ['Experience', 20, "Do you own or have you owned a pet?", "Yes, I understand pet behaviour and responsibilities.", 5, 0, 0, 'q_own_pet'],
                
                ['Job Commitment', 15, "Is this full-time or part-time for you? How serious are you about this work?", "I am serious about this work and can commit consistently.", 8, 0, 0, 'q_commitment'],
                ['Job Commitment', 15, "Do you agree to follow Petsfolio rules and not take clients offline?", "Yes, I will strictly follow Petsfolio policies.", 7, 0, 0, 'q_follow_rules'],
                
>>>>>>> origin/main
                ['Situational Safety & Presence of Mind', 15, "If the dog suddenly runs away during a walk, what will you do?", "I will stay calm, safely try to stop/call the dog, and immediately inform the client and team if needed.", 3, 0, 0, 'q_run_away_action'],
                ['Situational Safety & Presence of Mind', 15, "What precautions will you take to ensure the dog does NOT run away during a walk?", "Proper leash grip, staying alert, avoiding distractions, and choosing safe routes.", 3, 0, 0, 'q_prevent_run_away'],
                ['Situational Safety & Presence of Mind', 15, "If another dog tries to attack the dog you are handling, what will you do?", "I will calmly move away, protect the dog, and avoid panic.", 2, 0, 0, 'q_dog_attack_action'],
                ['Situational Safety & Presence of Mind', 15, "What precautions will you take to avoid fights or attacks from other dogs during a walk?", "I will maintain distance from unknown dogs and stay aware of surroundings.", 2, 0, 0, 'q_prevent_attacks'],
                ['Situational Safety & Presence of Mind', 15, "If the dog gets injured or meets with an accident during service, what will you do?", "I will immediately inform the pet parent and Petsfolio team and seek medical help if required.", 2, 0, 0, 'q_injury_action'],
                ['Situational Safety & Presence of Mind', 15, "What precautions will you take to avoid accidents or injuries during service?", "I will avoid risky areas, stay attentive, and ensure safe handling throughout the walk.", 2, 0, 0, 'q_prevent_accidents'],
                ['Situational Safety & Presence of Mind', 15, "If the dog suddenly becomes sick or unwell during service, what will you do?", "I will stop the service, inform the parent/team immediately, and seek medical support.", 1, 0, 0, 'q_sickness_action'],
<<<<<<< HEAD

=======
                
>>>>>>> origin/main
                ['Punctuality & Travel', 10, "Will you be punctual for every service?", "Yes, I understand punctuality is important.", 4, 0, 0, 'q_punctuality'],
                ['Punctuality & Travel', 10, "If you are getting late for a service, what will you do?", "I will inform the client and Petsfolio team in advance.", 3, 0, 0, 'q_late_action'],
                ['Punctuality & Travel', 10, "How far can you travel for services?", "Flexible and realistic travel commitment.", 1, 0, 0, 'q_travel_limit'],
                ['Punctuality & Travel', 10, "What is your mode of transportation?", "Own vehicle or reliable transportation method.", 2, 0, 0, 'q_transport'],
<<<<<<< HEAD

                ['Mobile Usage Discipline', 5, "If you receive a phone call while walking a dog, what will you do?", "I will avoid attending the call and focus on the dog’s safety.", 3, 0, 0, 'q_phone_call'],
                ['Mobile Usage Discipline', 5, "What risks can happen if you use the phone while walking a dog?", "The dog may run away, eat something dangerous, pull suddenly, or get injured.", 2, 0, 0, 'q_phone_risk'],

                ['Human Behaviour with Clients', 5, "How will you handle a difficult or upset pet parent during a service?", "I will stay calm, polite, avoid arguments, and inform the Petsfolio team if required.", 2, 0, 0, 'q_difficult_parent'],
                ['Human Behaviour with Clients', 5, "If a client asks you to do extra work or handle another pet not booked, what will you do?", "I will politely ask them to book through the Petsfolio app.", 2, 0, 0, 'q_extra_work'],
                ['Human Behaviour with Clients', 5, "What kind of dress code will you follow while going for a service?", "Clean, comfortable clothes and proper footwear suitable for pet handling.", 1, 0, 0, 'q_dress_code'],

=======
                
                ['Mobile Usage Discipline', 5, "If you receive a phone call while walking a dog, what will you do?", "I will avoid attending the call and focus on the dog’s safety.", 3, 0, 0, 'q_phone_call'],
                ['Mobile Usage Discipline', 5, "What risks can happen if you use the phone while walking a dog?", "The dog may run away, eat something dangerous, pull suddenly, or get injured.", 2, 0, 0, 'q_phone_risk'],
                
                ['Human Behaviour with Clients', 5, "How will you handle a difficult or upset pet parent during a service?", "I will stay calm, polite, avoid arguments, and inform the Petsfolio team if required.", 2, 0, 0, 'q_difficult_parent'],
                ['Human Behaviour with Clients', 5, "If a client asks you to do extra work or handle another pet not booked, what will you do?", "I will politely ask them to book through the Petsfolio app.", 2, 0, 0, 'q_extra_work'],
                ['Human Behaviour with Clients', 5, "What kind of dress code will you follow while going for a service?", "Clean, comfortable clothes and proper footwear suitable for pet handling.", 1, 0, 0, 'q_dress_code'],
                
>>>>>>> origin/main
                ['Trustworthiness & Safety', 15, "Are you comfortable with police/background verification?", "Yes, I am comfortable with verification.", 3, 0, 0, 'q_verification'],
                ['Trustworthiness & Safety', 15, "Have you ever handled pets independently at client homes?", "Yes, I have responsibly handled pets independently.", 3, 0, 0, 'q_independent_handling'],
                ['Trustworthiness & Safety', 15, "Are you willing to follow ethical service policies?", "Yes, I will follow all ethical and platform policies.", 3, 0, 0, 'q_ethics'],
                ['Trustworthiness & Safety', 15, "Would you agree not to take bookings outside the app?", "Yes, I will only accept bookings through Petsfolio.", 4, 0, 0, 'q_no_offline'],
                ['Trustworthiness & Safety', 15, "Can clients trust you with home/pet access responsibilities?", "Yes, I understand the responsibility and will behave professionally and safely.", 2, 0, 0, 'q_trust_access'],
                ['Ethics & Platform Discipline', 0, "Do you understand that taking clients offline will result in a penalty and account block?", "Instruction to be read by Executive: Taking clients offline is strictly not allowed.", 0, 0, 0, 'q_offline_warning'],
            ];

            foreach ($walking as $idx => $q) {
                $seedData[] = [
                    'service_id'      => 4,
                    'category_name'   => $q[0],
                    'category_weight' => $q[1],
                    'question_text'   => $q[2],
                    'hint_text'       => $q[3],
                    'options'         => $commonOptions,
                    'weight'          => $q[4],
                    'is_trigger'      => $q[5],
                    'is_dependent'    => $q[6],
                    'field_name'      => $q[7],
<<<<<<< HEAD
                    'training_type'   => null,
=======
>>>>>>> origin/main
                    'sort_order'      => $idx + 1,
                    'created_at'      => date('Y-m-d H:i:s'),
                    'updated_at'      => date('Y-m-d H:i:s'),
                ];
            }

            // --- GROOMING (ID 2) ---
            $grooming = [
<<<<<<< HEAD
                // --- Grooming & Trimming ---
                ['Behaviour, Psychology & Pet Handling', 25, "Why did you choose to work with pets?", "Comfortable with pets, love pets & patient nature, deep comfort and calm with pets.", 5, 0, 0, 'g_why_work_with_pets', 'grooming_and_trimming', 'Bad:For money only,Poor:Like pets but scared,Average:Comfortable with pets,Good:Love pets, patient,Excellent:Deep comfort, very calm with pets'],
                ['Behaviour, Psychology & Pet Handling', 25, "If a dog tries to bite or misbehaves during grooming, what will you do?", "Stay calm, give space, control safely, explains calming + safe handling method clearly.", 8, 0, 0, 'g_bite_handling', 'grooming_and_trimming', 'Bad:Cannot handle,Poor:Avoid such dogs,Average:Try slowly without clear method,Good:Stay calm, give space, control safely,Excellent:Explains calming + safe handling method clearly'],
                ['Behaviour, Psychology & Pet Handling', 25, "How do you handle a scared dog?", "Gentle talk, patience, trust-building approach before grooming.", 6, 0, 0, 'g_scared_dog', 'grooming_and_trimming', 'Bad:Force grooming,Poor:Rush the process,Average:Go slowly,Good:Gentle talk, patience,Excellent:Trust-building approach before grooming'],
                ['Behaviour, Psychology & Pet Handling', 25, "Share one difficult situation you handled with a dog.", "Real case handled calmly, critical case handled professionally.", 6, 0, 0, 'g_difficult_situation', 'grooming_and_trimming', 'Bad:No experience,Poor:Vague answer,Average:Basic situation,Good:Real case handled calmly,Excellent:Critical case handled professionally'],

                ['Experience', 34, "What is the total expertise you have in grooming?", "1–2 years experience, 2+ years professional.", 10, 0, 0, 'g_total_experience', 'grooming_and_trimming', 'Bad:No experience,Poor:Helped others / basic exposure,Average:3–6 months work,Good:1–2 years experience,Excellent:2+ years professional'],
                ['Experience', 34, "What all tools you carry for Basic Grooming?", "Mentions slicker brush, steel comb, dryer/blower. Clearly lists shampoo types, towels, nail cutter, ear cleaner, slicker, comb, dryer.", 8, 0, 0, 'g_basic_tools', 'grooming_and_trimming', 'Bad:Cannot name tools / uses client items,Poor:Mentions 2–3 items (shampoo, towel),Average:Mentions shampoo, towel, nail cutter, ear cleaner, brush/comb,Good:Mentions above + slicker brush, steel comb, dryer/blower,Excellent:Clearly lists: shampoo types, towels, nail cutter, styptic powder, ear cleaner, slicker, comb, dryer'],
                ['Experience', 34, "What all tools you carry for Trimming?", "Mentions clipper, multiple blades, scissors. Mentions clipper, multiple blades (sizes), straight & curved scissors, thinning scissors.", 8, 0, 0, 'g_trimming_tools', 'grooming_and_trimming', 'Bad:Cannot explain trimming tools,Poor:Mentions only clipper machine,Average:Mentions clipper + 1 blade,Good:Mentions clipper, multiple blades, scissors,Excellent:Mentions: clipper, multiple blades (sizes), straight & curved scissors, thinning scissors'],
                ['Experience', 34, "Can you do medicated bath for skin issues?", "Explains using medicated shampoo based on skin issue (dandruff, fungal, allergies), clearly explains contact time, wash, and drying.", 8, 0, 0, 'g_medicated_bath', 'grooming_and_trimming', 'Bad:Says no / never done,Poor:Says “I will use any shampoo”,Average:Knows medicated shampoo but cannot explain usage,Good:Explains using medicated shampoo based on skin issue (dandruff, fungal, allergies),Excellent:Clearly explains types of medicated baths, contact time, careful wash, and drying process'],

                ['Situational Safety & Presence of Mind', 22, "Can you calm and control nervous/aggressive dogs?", "Explains calming steps (slow approach, gentle hold, patience), clearly explains safety hold, calming techniques.", 8, 0, 0, 'g_aggressive_control', 'grooming_and_trimming', 'Bad:Says cannot handle such dogs,Poor:Says it is very difficult and avoids them,Average:Says will try slowly but no clear method,Good:Explains calming steps (slow approach, gentle hold, patience),Excellent:Clearly explains handling methods, safety hold, calming techniques, and experience'],
                ['Situational Safety & Presence of Mind', 22, "How do you ensure pet safety during grooming?", "Explains safe holding, proper tools, and patience, explains full safety process: checking tools, safe hold, calm handling.", 5, 0, 0, 'g_safety_measures', 'grooming_and_trimming', 'Bad:No clear safety steps,Poor:Says “I will be careful” only,Average:Mentions basic care like slow work,Good:Explains safe holding, proper tools, and patience,Excellent:Explains full safety process: checking tools, safe hold, calm handling, no rush'],
                ['Situational Safety & Presence of Mind', 22, "If a dog gets a small cut during trimming, what will you do?", "First aid and inform pet parent, full first aid, calm dog, inform, prevent repeat.", 4, 0, 0, 'g_cut_handling', 'grooming_and_trimming', 'Bad:Hide the issue,Poor:Says it won’t happen,Average:Clean the area,Good:First aid and inform pet parent,Excellent:Full first aid, calm dog, inform, prevent repeat'],
                ['Situational Safety & Presence of Mind', 22, "How do you handle a dog that keeps moving during trimming?", "Explains calming, safe hold, short breaks, explains proper control techniques, patience, and safe trimming method.", 5, 0, 0, 'g_moving_dog', 'grooming_and_trimming', 'Bad:Cannot manage,Poor:Says it is very difficult,Average:Says will try slowly,Good:Explains calming, safe hold, short breaks,Excellent:Explains proper control techniques, patience, and safe trimming method'],

                ['Mobile Usage Discipline', 7, "If you get a personal phone call during grooming, what will you do?", "Avoid calls during grooming, full focus on pet, call later.", 4, 0, 0, 'g_phone_call', 'grooming_and_trimming', 'Bad:Attend the call,Poor:Short call,Average:Ignore mostly,Good:Avoid calls during grooming,Excellent:Full focus on pet, call later'],
                ['Mobile Usage Discipline', 7, "What risks if you use phone during grooming?", "Injury risk, clear safety risks explained.", 3, 0, 0, 'g_phone_risks', 'grooming_and_trimming', 'Bad:No risk,Poor:Minor risk,Average:Pet may move,Good:Injury risk,Excellent:Clear safety risks explained'],

                ['Client Interaction', 8, "How will you handle a difficult pet parent?", "Calm and polite, professional and inform team if needed.", 4, 0, 0, 'g_client_handling', 'grooming_and_trimming', 'Bad:Argue,Poor:Get irritated,Average:Stay quiet,Good:Calm and polite,Excellent:Professional and inform team if needed'],
                ['Client Interaction', 8, "After grooming, how do you clean the area?", "Proper cleaning, full clean, hygienic finish.", 4, 0, 0, 'g_cleaning_after', 'grooming_and_trimming', 'Bad:Leave the mess,Poor:Basic wipe,Average:Clean visible hair,Good:Proper cleaning,Excellent:Full clean, hygienic finish'],

                // --- Only Grooming ---
                ['Behaviour, Psychology & Pet Handling', 25, "Why did you choose to work with pets?", "Comfortable with pets, love pets & patient nature, deep comfort and calm with pets.", 5, 0, 0, 'g_only_why_work_with_pets', 'grooming', 'Bad:For money only,Poor:Like pets but scared,Average:Comfortable with pets,Good:Love pets, patient,Excellent:Deep comfort, very calm with pets'],
                ['Behaviour, Psychology & Pet Handling', 25, "If a dog tries to bite or misbehaves during grooming, what will you do?", "Stay calm, give space, control safely, explains calming + safe handling method clearly.", 8, 0, 0, 'g_only_bite_handling', 'grooming', 'Bad:Cannot handle,Poor:Avoid such dogs,Average:Try slowly without clear method,Good:Stay calm, give space, control safely,Excellent:Explains calming + safe handling method clearly'],
                ['Behaviour, Psychology & Pet Handling', 25, "How do you handle a scared dog?", "Gentle talk, patience, trust-building approach before grooming.", 6, 0, 0, 'g_only_scared_dog', 'grooming', 'Bad:Force grooming,Poor:Rush the process,Average:Go slowly,Good:Gentle talk, patience,Excellent:Trust-building approach before grooming'],
                ['Behaviour, Psychology & Pet Handling', 25, "Share one difficult situation you handled with a dog.", "Real case handled calmly, critical case handled professionally.", 6, 0, 0, 'g_only_difficult_situation', 'grooming', 'Bad:No experience,Poor:Vague answer,Average:Basic situation,Good:Real case handled calmly,Excellent:Critical case handled professionally'],

                ['Experience', 30, "What is the total expertise you have in grooming?", "1–2 years experience, 2+ years professional.", 10, 0, 0, 'g_only_total_experience', 'grooming', 'Bad:No experience,Poor:Helped others / basic exposure,Average:3–6 months work,Good:1–2 years experience,Excellent:2+ years professional'],
                ['Experience', 30, "What all tools you carry for Basic Grooming?", "Mentions slicker brush, steel comb, dryer/blower. Clearly lists shampoo types, towels, nail cutter, ear cleaner, slicker, comb, dryer.", 10, 0, 0, 'g_only_basic_tools', 'grooming', 'Bad:Cannot name tools / uses client items,Poor:Mentions 2–3 items (shampoo, towel),Average:Mentions shampoo, towel, nail cutter, ear cleaner, brush/comb,Good:Mentions above + slicker brush, steel comb, dryer/blower,Excellent:Clearly lists: shampoo types, towels, nail cutter, styptic powder, ear cleaner, slicker, comb, dryer'],
                ['Experience', 30, "Can you do medicated bath for skin issues?", "Explains using medicated shampoo based on skin issue, clearly explains medicated bath process, contact time, and drying.", 10, 0, 0, 'g_only_medicated_bath', 'grooming', 'Bad:Says no / never done,Poor:Says “I will use any shampoo”,Average:Knows medicated shampoo but cannot explain usage,Good:Explains using medicated shampoo based on skin issue,Excellent:Clearly explains medicated bath process, contact time, and drying'],

                ['Situational Safety & Presence of Mind', 28, "Can you calm and control nervous/aggressive dogs?", "Explains calming steps, clearly explains handling methods with experience.", 8, 0, 0, 'g_only_aggressive_control', 'grooming', 'Bad:Says cannot handle such dogs,Poor:Says it is very difficult and avoids them,Average:Says will try slowly but no clear method,Good:Explains calming steps,Excellent:Clearly explains handling methods with experience'],
                ['Situational Safety & Presence of Mind', 28, "How do you ensure pet safety during grooming?", "Explains safe holding, proper tools, patience, full safety process explained.", 8, 0, 0, 'g_only_safety_measures', 'grooming', 'Bad:No clear safety steps,Poor:Says “I will be careful” only,Average:Mentions basic care,Good:Explains safe holding, proper tools, patience,Excellent:Full safety process explained'],
                ['Situational Safety & Presence of Mind', 28, "If a dog gets minor injury (nail bleeding / skin irritation) during grooming, what will you do?", "First aid and inform pet parent, proper first aid, calm dog, inform, prevent repeat.", 6, 0, 0, 'g_only_cut_handling', 'grooming', 'Bad:Hide the issue,Poor:Says it won’t happen,Average:Clean the area,Good:First aid and inform pet parent,Excellent:Proper first aid, calm dog, inform, prevent repeat'],
                ['Situational Safety & Presence of Mind', 28, "How do you handle a dog that keeps moving during grooming?", "Explains calming, safe hold, short breaks, explains proper control techniques with patience.", 6, 0, 0, 'g_only_moving_dog', 'grooming', 'Bad:Cannot manage,Poor:Says it is very difficult,Average:Says will try slowly,Good:Explains calming, safe hold, short breaks,Excellent:Explains proper control techniques with patience'],

                ['Mobile Usage Discipline', 7, "If you get a personal phone call during grooming, what will you do?", "Avoid calls during grooming, full focus on pet, call later.", 4, 0, 0, 'g_only_phone_call', 'grooming', 'Bad:Attend the call,Poor:Short call,Average:Ignore mostly,Good:Avoid calls during grooming,Excellent:Full focus on pet, call later'],
                ['Mobile Usage Discipline', 7, "What risks if you use phone during grooming?", "Injury risk, clear safety risks explained.", 3, 0, 0, 'g_only_phone_risks', 'grooming', 'Bad:No risk,Poor:Minor risk,Average:Pet may move,Good:Injury risk,Excellent:Clear safety risks explained'],

                ['Client Interaction', 10, "How will you handle a difficult pet parent?", "Calm and polite, professional and inform team if needed.", 4, 0, 0, 'g_only_client_handling', 'grooming', 'Bad:Argue,Poor:Get irritated,Average:Stay quiet,Good:Calm and polite,Excellent:Professional and inform team if needed'],
                ['Client Interaction', 10, "After grooming, how do you clean the area?", "Proper cleaning, full clean, hygienic finish.", 6, 0, 0, 'g_only_cleaning_after', 'grooming', 'Bad:Leave the mess,Poor:Basic wipe,Average:Clean visible hair,Good:Proper cleaning,Excellent:Full clean, hygienic finish'],

                // --- Grooming + Stylish Hair Cut ---
                ['Behaviour, Psychology & Pet Handling', 25, "Why did you choose to work with pets?", "Love pets, patient nature, deep comfort, calm and passionate about grooming.", 5, 0, 0, 'g_hair_why_work_with_pets', 'grooming_and_cut', 'Bad:For money only,Poor:Like pets but scared of grooming work,Average:Comfortable with pets,Good:Love pets, patient nature,Excellent:Deep comfort, calm and passionate about grooming'],
                ['Behaviour, Psychology & Pet Handling', 25, "If a dog becomes aggressive during grooming or styling, what will you do?", "Stay calm, give space, control safely, uses proper calming + handling techniques confidently.", 8, 0, 0, 'g_hair_bite_handling', 'grooming_and_cut', 'Bad:Cannot handle,Poor:Avoid such dogs,Average:Try slowly without method,Good:Stay calm, give space, control safely,Excellent:Uses proper calming + handling techniques confidently'],
                ['Behaviour, Psychology & Pet Handling', 25, "How do you handle a scared dog during haircut?", "Gentle talk, patience, trust-building + step-by-step calming approach.", 6, 0, 0, 'g_hair_scared_dog', 'grooming_and_cut', 'Bad:Force grooming,Poor:Rush the process,Average:Go slowly,Good:Gentle talk, patience,Excellent:Trust-building + step-by-step calming approach'],
                ['Behaviour, Psychology & Pet Handling', 25, "Share one difficult grooming/cutting situation you handled.", "Real case handled calmly, complex grooming case handled professionally.", 6, 0, 0, 'g_hair_difficult_situation', 'grooming_and_cut', 'Bad:No experience,Poor:Vague answer,Average:Basic situation,Good:Real case handled calmly,Excellent:Complex grooming case handled professionally'],

                ['Experience', 54, "Total experience in grooming & styling haircuts", "1-2 years experience, 2+ years professional styling experience.", 10, 0, 0, 'g_hair_total_experience', 'grooming_and_cut', 'Bad:No experience,Poor:Assisted only,Average:3–6 months,Good:1–2 years,Excellent:2+ years professional styling experience'],
                ['Experience', 54, "What all tools you carry for Grooming?", "Mentions slicker brush, steel comb, dryer/blower. Clearly lists shampoo types, towels, nail cutter, ear cleaner, slicker, comb, dryer.", 8, 0, 0, 'g_hair_basic_tools', 'grooming_and_cut', 'Bad:Cannot name tools / uses client items,Poor:Mentions 2–3 items (shampoo, towel),Average:Mentions shampoo, towel, nail cutter, ear cleaner, brush/comb,Good:Mentions above + slicker brush, steel comb, dryer/blower,Excellent:Clearly lists: shampoo types, towels, nail cutter, styptic powder, ear cleaner, slicker, comb, dryer'],
                ['Experience', 54, "What all tools do you carry for Stylish Hair Cutting?", "Clipper + multiple blades + scissors. Full styling kit (clippers, blades, scissors set, combs, styling tools).", 10, 0, 0, 'g_hair_trimming_tools', 'grooming_and_cut', 'Bad:Cannot name tools,Poor:Only clipper,Average:Clipper + basic blade,Good:Clipper + multiple blades + scissors,Excellent:Full styling kit (clippers, blades, scissors set, combs, styling tools)'],
                ['Experience', 54, "Can you do breed-specific stylish cuts?", "Can do common breeds, expert in breed styling (Poodle, Shih Tzu, etc.).", 10, 0, 0, 'g_hair_breed_cuts', 'grooming_and_cut', 'Bad:Cannot do,Poor:Seen only,Average:Basic trimming only,Good:Can do common breeds,Excellent:Expert in breed styling (Poodle, Shih Tzu, etc.)'],
                ['Experience', 54, "Name some dog hair cut styles", "Mentions 3–4 common styles (puppy, summer, teddy, hygienic). Mentions multiple breed styles + explains Poodle, Shih Tzu, teddy, summer, etc.", 8, 0, 0, 'g_hair_cut_styles', 'grooming_and_cut', 'Bad:Cannot name any,Poor:Says “short cut / normal cut” only,Average:Mentions 1–2 basic styles (summer cut, teddy cut vaguely),Good:Mentions 3–4 common styles (puppy cut, summer cut, teddy cut, hygienic cut),Excellent:Mentions multiple breed styles + clearly explains (Poodle cut, Shih Tzu cut, teddy cut, summer cut, hygienic cut, breed-specific styling)'],
                ['Experience', 54, "Can you do medicated bath for skin issues?", "Uses based on skin issue, full medicated bath process knowledge.", 8, 0, 0, 'g_hair_medicated_bath', 'grooming_and_cut', 'Bad:Never done,Poor:Any shampoo use,Average:Basic idea,Good:Uses based on skin issue,Excellent:Full medicated bath process knowledge'],

                ['Situational Safety & Control', 28, "Can you calm and control aggressive dogs during grooming?", "Calm control methods, expert handling with experience.", 8, 0, 0, 'g_hair_aggressive_control', 'grooming_and_cut', 'Bad:Cannot handle,Poor:Avoids dogs,Average:Tries slowly,Good:Calm control methods,Excellent:Expert handling with experience'],
                ['Situational Safety & Control', 28, "How do you ensure pet safety during grooming & haircut?", "Safe hold + patience, full safety system + proper handling.", 8, 0, 0, 'g_hair_safety_measures', 'grooming_and_cut', 'Bad:No safety steps,Poor:“Be careful” only,Average:Basic care,Good:Safe hold + patience,Excellent:Full safety system + proper handling'],
                ['Situational Safety & Control', 28, "If a dog gets a small cut during haircut, what will you do?", "First aid + inform owner, proper first aid + prevention + calm handling.", 6, 0, 0, 'g_hair_cut_handling', 'grooming_and_cut', 'Bad:Hide it,Poor:Ignore,Average:Clean only,Good:First aid + inform owner,Excellent:Proper first aid + prevention + calm handling'],
                ['Situational Safety & Control', 28, "How do you handle a dog that keeps moving during haircut?", "Calm, short breaks, professional handling + control technique.", 6, 0, 0, 'g_hair_moving_dog', 'grooming_and_cut', 'Bad:Cannot manage,Poor:Very difficult,Average:Try slowly,Good:Calm, short breaks,Excellent:Professional handling + control technique'],

                ['Mobile Usage Discipline', 7, "If you get a phone call during grooming, what will you do?", "Avoid calls, full focus on pet.", 4, 0, 0, 'g_hair_phone_call', 'grooming_and_cut', 'Bad:Attend call,Poor:Short call,Average:Ignore mostly,Good:Avoid calls,Excellent:Full focus on pet'],
                ['Mobile Usage Discipline', 7, "What risks if you use phone during grooming?", "Injury risk, clear safety explanation.", 3, 0, 0, 'g_hair_phone_risks', 'grooming_and_cut', 'Bad:No risk,Poor:Minor risk,Average:Pet may move,Good:Injury risk,Excellent:Clear safety explanation'],

                ['Client Interaction', 4, "How will you handle a difficult pet parent?", "Calm and polite, professional + escalate if needed.", 4, 0, 0, 'g_hair_client_handling', 'grooming_and_cut', 'Bad:Argue,Poor:Get irritated,Average:Stay quiet,Good:Calm and polite,Excellent:Professional + escalate if needed'],
=======
                ['Behaviour, Pet Handling & Grooming Approach', 30, "Why did you choose to become a dog groomer?", "Love for dogs, comfort in handling them, interest in grooming.", 3, 0, 0, 'g_why_groomer'],
                ['Behaviour, Pet Handling & Grooming Approach', 30, "How do you handle a dog that is scared or nervous for grooming?", "Slow approach, calm voice, give time, no force.", 5, 0, 0, 'g_handle_scared'],
                ['Behaviour, Pet Handling & Grooming Approach', 30, "What signs show a dog is stressed during grooming?", "Shaking, tail down, trying to escape, whining, growling.", 5, 0, 0, 'g_stress_signs'],
                ['Behaviour, Pet Handling & Grooming Approach', 30, "What should a groomer never do during grooming and why?", "Never hit, shout, rush, or force. It can hurt the dog and break trust.", 5, 0, 0, 'g_never_do'],
                ['Behaviour, Pet Handling & Grooming Approach', 30, "How do you make a new dog comfortable before starting grooming?", "Let the dog sniff tools, talk softly, gentle touch.", 4, 0, 0, 'g_make_comfortable'],
                ['Behaviour, Pet Handling & Grooming Approach', 30, "How do you make grooming a stress-free experience for the dog?", "Patience, breaks, gentle handling, positive behaviour.", 4, 0, 0, 'g_stress_free'],
                ['Behaviour, Pet Handling & Grooming Approach', 30, "What precautions do you take before starting grooming?", "Check dog behaviour, tools ready, safe setup.", 4, 0, 0, 'g_precautions'],
                
                ['Grooming Experience & Qualification', 20, "Explain your practical experience in dog grooming.", "Hands-on grooming of different breeds and temperaments.", 8, 1, 0, 'g_exp_detail'],
                ['Grooming Experience & Qualification', 20, "How many years of grooming experience do you have?", "Clear timeline and work details.", 5, 0, 1, 'g_years_exp'],
                ['Grooming Experience & Qualification', 20, "What grooming services can you do?", "Bathing, drying, nail cutting, ear cleaning, hair trimming, hygiene trim.", 4, 0, 0, 'g_services_list'],
                ['Grooming Experience & Qualification', 20, "Do you have any grooming certification? From where?", "Course or certification details.", 3, 0, 0, 'g_certification'],
                
                ['Grooming Knowledge & Process', 15, "How do you decide which shampoo or products to use for a dog?", "Based on coat type, skin condition, breed.", 5, 0, 0, 'g_shampoo_choice'],
                ['Grooming Knowledge & Process', 15, "How long should a grooming session take and why?", "Proper time without rushing to avoid stress.", 3, 0, 0, 'g_session_duration'],
                ['Grooming Knowledge & Process', 15, "What is the role of the pet parent during grooming?", "Share dog behaviour, health issues, and follow after-care advice.", 4, 0, 0, 'g_parent_role'],
                ['Grooming Knowledge & Process', 15, "How do you maintain hygiene of your grooming tools?", "Clean and sanitize tools before and after every session.", 3, 0, 0, 'g_tool_hygiene'],
                
                ['Situational Handling & Safety', 15, "If a dog tries to bite during grooming, what will you do?", "Stay calm, pause, handle gently, no force.", 3, 0, 0, 'g_bite_action'],
                ['Situational Handling & Safety', 15, "If the dog gets a small cut or injury during grooming, what will you do?", "Inform pet parent and Petsfolio immediately, give first aid.", 3, 0, 0, 'g_injury_action'],
                ['Situational Handling & Safety', 15, "What precautions do you take while nail cutting and trimming?", "Careful handling, proper tools, slow process.", 3, 0, 0, 'g_nail_precautions'],
                ['Situational Handling & Safety', 15, "How do you ensure the dog does not slip or fall during grooming?", "Non-slip mat, proper holding, safe setup.", 3, 0, 0, 'g_prevent_fall'],
                ['Situational Handling & Safety', 15, "How do you make sure the dog does not get over-stressed?", "Watch body language, give breaks.", 3, 0, 0, 'g_prevent_stress'],
                
                ['Job Commitment & Platform Discipline', 10, "Is this full-time or part-time for you? How serious are you?", "Serious and regular commitment.", 5, 0, 0, 'g_commitment'],
                ['Job Commitment & Platform Discipline', 10, "Why is it important to follow Petsfolio rules and policies?", "For safety, trust, and proper service.", 5, 0, 0, 'g_follow_rules'],
                
                ['Client Interaction & Professionalism', 7, "How will you handle a pet parent who is unhappy or impatient?", "Stay calm, explain politely.", 2, 0, 0, 'g_difficult_parent'],
                ['Client Interaction & Professionalism', 7, "If a client asks you to rush grooming, what will you do?", "Explain that rushing can stress or hurt the dog.", 2, 0, 0, 'g_rush_request'],
                ['Client Interaction & Professionalism', 7, "If a client asks you to do extra work or handle another pet not booked, what will you do?", "Ask them to book through the Petsfolio app.", 2, 0, 0, 'g_extra_work'],
                ['Client Interaction & Professionalism', 7, "What dress code and behaviour will you maintain?", "Clean clothes, proper shoes, polite behaviour.", 1, 0, 0, 'g_dress_code'],
                
                ['Trustworthiness, Ethics & Real Experience', 13, "Why should a client trust you with their home and pet?", "Responsible, professional, respect safety.", 3, 0, 0, 'g_trust_reason'],
                ['Trustworthiness, Ethics & Real Experience', 13, "Explain how you follow safe and hygienic grooming practices at client homes?", "Clean tools, safe setup, no force.", 3, 0, 0, 'g_home_practice'],
                ['Trustworthiness, Ethics & Real Experience', 13, "Why is police/background verification important for this job?", "Builds trust and ensures safety.", 3, 0, 0, 'g_verification'],
                ['Trustworthiness, Ethics & Real Experience', 13, "Tell me about one difficult grooming case you handled.", "Real example, calm handling.", 2, 0, 0, 'g_case_study'],
                ['Trustworthiness, Ethics & Real Experience', 13, "Tell me about a time when a dog was not cooperating. What did you do?", "Patience, changed approach.", 1, 0, 0, 'g_non_coop'],
                ['Trustworthiness, Ethics & Real Experience', 13, "Tell me about a time when you corrected a pet parent. How did you explain?", "Politely educated, no argument.", 1, 0, 0, 'g_parent_educate'],
                ['Ethics & Platform Discipline', 0, "Do you understand that taking clients offline will result in a penalty and account block?", "Instruction to be read by Executive: Taking clients offline is strictly not allowed.", 0, 0, 0, 'g_offline_warning'],
>>>>>>> origin/main
            ];

            foreach ($grooming as $idx => $q) {
                $seedData[] = [
                    'service_id'      => 2,
                    'category_name'   => $q[0],
                    'category_weight' => $q[1],
                    'question_text'   => $q[2],
                    'hint_text'       => $q[3],
<<<<<<< HEAD
=======
                    'options'         => $commonOptions,
>>>>>>> origin/main
                    'weight'          => $q[4],
                    'is_trigger'      => $q[5],
                    'is_dependent'    => $q[6],
                    'field_name'      => $q[7],
<<<<<<< HEAD
                    'training_type'   => $q[8] ?? null,
                    'options'         => $q[9] ?? $commonOptions,
=======
>>>>>>> origin/main
                    'sort_order'      => $idx + 1,
                    'created_at'      => date('Y-m-d H:i:s'),
                    'updated_at'      => date('Y-m-d H:i:s'),
                ];
            }

            // --- TRAINING (ID 3) ---
            $training = [
<<<<<<< HEAD
                // --- Puppy & Obedience Training ---
                ['Behaviour, Psychology & Training Approach', 27, "Why did you choose to become a dog trainer?", "Love teaching dogs & improving behaviour, passionate about dog learning & obedience building.", 5, 0, 0, 't_puppy_why_trainer', 'obedience_training', 'Bad:For money only,Poor:Like dogs but no clarity,Average:Interested in training basics,Good:Love teaching dogs & improving behaviour,Excellent:Passionate about dog learning & obedience building'],
                ['Behaviour, Psychology & Training Approach', 27, "How do you build trust with a new puppy?", "Gentle interaction + treats + patience, structured bonding + positive reinforcement method.", 8, 0, 0, 't_puppy_build_trust', 'obedience_training', 'Bad:Force commands,Poor:Ignore bonding,Average:Slowly interact,Good:Gentle interaction + treats + patience,Excellent:Structured bonding + positive reinforcement method'],
                ['Behaviour, Psychology & Training Approach', 27, "What is your approach to obedience training?", "Positive reinforcement, structured obedience program with reward-based system.", 8, 0, 0, 't_puppy_obedience_approach', 'obedience_training', 'Bad:Force-based training,Poor:No clear method,Average:Basic command teaching,Good:Positive reinforcement (sit, stay, come),Excellent:Structured obedience program with reward-based system'],
                ['Behaviour, Psychology & Training Approach', 27, "How do you handle a puppy that refuses to listen?", "Patience + repetition, modify technique + reward-based correction.", 6, 0, 0, 't_puppy_refuse_listen', 'obedience_training', 'Bad:Get angry,Poor:Give up,Average:Try multiple times,Good:Use patience + repetition,Excellent:Modify technique + reward-based correction'],

                ['Experience', 34, "What is your total experience in puppy & obedience training?", "1–2 years, 2+ years professional training.", 10, 0, 0, 't_puppy_experience', 'obedience_training', 'Bad:No experience,Poor:Assisted someone,Average:3–6 months,Good:1–2 years,Excellent:2+ years professional training'],
                ['Experience', 34, "What types of dogs have you trained?", "Multiple breeds, wide breed experience.", 8, 0, 0, 't_puppy_dog_types', 'obedience_training', 'Bad:None,Poor:Only own dog,Average:Few basic breeds,Good:Multiple breeds,Excellent:Wide breed experience (puppies + adult dogs)'],
                ['Experience', 34, "What basic commands can you teach?", "Sit, stay, come, down, full obedience set + leash walking discipline.", 8, 0, 0, 't_puppy_commands', 'obedience_training', 'Bad:None,Poor:Only sit,Average:Sit + stay,Good:Sit, stay, come, down,Excellent:Full obedience set + leash walking discipline'],
                ['Experience', 34, "How do you correct bad behaviour in puppies?", "Redirect + reward system, structured behaviour correction plan.", 8, 0, 0, 't_puppy_correct_behaviour', 'obedience_training', 'Bad:Punishment,Poor:Shouting,Average:Ignore behaviour,Good:Redirect + reward system,Excellent:Structured behaviour correction plan with positive reinforcement'],

                ['Situational Handling & Safety', 20, "If a puppy bites during training, what will you do?", "Redirect behaviour calmly, behavior correction + calm training reset.", 6, 0, 0, 't_puppy_bite_handling', 'obedience_training', 'Bad:Hit puppy,Poor:Get angry,Average:Stop session,Good:Redirect behaviour calmly,Excellent:Behaviour correction + calm training reset'],
                ['Situational Handling & Safety', 20, "How do you ensure safety during training sessions?", "Controlled environment, structured safety-first training approach.", 8, 0, 0, 't_puppy_safety', 'obedience_training', 'Bad:No safety awareness,Poor:Basic caution,Average:Normal supervision,Good:Controlled environment,Excellent:Structured safety-first training approach'],
                ['Situational Handling & Safety', 20, "What will you do if puppy gets stressed during training?", "Pause/take break + calm puppy, adjust training pace.", 6, 0, 0, 't_puppy_stress_handling', 'obedience_training', 'Bad:Continue training,Poor:Ignore,Average:Pause briefly,Good:Take break + calm puppy,Excellent:Adjust training pace + reduce stress triggers'],

                ['Discipline & Professional Behaviour', 8, "If you get distracted or receive a phone call during training, what will you do?", "Avoid calls, full focus on training session.", 4, 0, 0, 't_puppy_phone_distraction', 'obedience_training', 'Bad:Attend call,Poor:Short call,Average:Ignore sometimes,Good:Avoid calls during session,Excellent:Full focus on training session'],
                ['Discipline & Professional Behaviour', 8, "What risks if trainer uses phone during training?", "Puppy may lose focus, safety + behavioural regression risk.", 4, 0, 0, 't_puppy_phone_risks', 'obedience_training', 'Bad:No risk,Poor:Minor distraction,Average:Puppy may lose focus,Good:Training failure risk,Excellent:Safety + behavioural regression risk clearly explained'],

                ['Client Interaction', 9, "How do you handle a confused pet parent during training?", "Patient explanation, clear structured guidance + demonstration.", 4, 0, 0, 't_puppy_parent_interaction', 'obedience_training', 'Bad:Ignore,Poor:Get irritated,Average:Basic explanation,Good:Patient explanation,Excellent:Clear structured guidance + demonstration'],
                ['Client Interaction', 9, "If owner does not follow training instructions, what will you do?", "Politely guide again, educate owner with structured home plan.", 5, 0, 0, 't_puppy_owner_noncompliance', 'obedience_training', 'Bad:Stop training,Poor:Argue,Average:Continue without correction,Good:Politely guide again,Excellent:Educate owner with structured home training plan'],

                // --- Behaviour Training ---
                ['Behaviour, Psychology & Training Approach', 27, "Why did you choose behaviour training as your specialization?", "Interested in correcting behaviour issues, passionate about complex problems.", 5, 0, 0, 't_behav_why_specialization', 'behavioral_training', 'Bad:For money only,Poor:General interest,Average:Likes handling dogs,Good:Interested in correcting behaviour issues,Excellent:Passionate about solving complex dog behaviour problems'],
                ['Behaviour, Psychology & Training Approach', 27, "What is your approach to aggressive dogs?", "Controlled desensitization, structured behaviour modification + safety-first.", 8, 0, 0, 't_behav_aggression_approach', 'behavioral_training', 'Bad:Use force,Poor:Avoid handling,Average:Basic calming,Good:Controlled desensitization + calm handling,Excellent:Structured behaviour modification + positive reinforcement + safety first approach'],
                ['Behaviour, Psychology & Training Approach', 27, "How do you handle separation anxiety in dogs?", "Gradual desensitization, structured behaviour plan + owner involvement.", 8, 0, 0, 't_behav_anxiety_handling', 'behavioral_training', 'Bad:Ignore issue,Poor:Don’t know,Average:Basic comfort only,Good:Gradual desensitization + routine building,Excellent:Structured behaviour plan with exposure training + owner involvement'],
                ['Behaviour, Psychology & Training Approach', 27, "What do you do if a dog shows sudden aggression during training?", "Calmly de-escalate, structured safety control + behaviour reset.", 6, 0, 0, 't_behav_sudden_aggression', 'behavioral_training', 'Bad:Hit dog,Poor:Panic,Average:Stop session,Good:Calmly de-escalate situation,Excellent:Structured safety control + behaviour reset technique'],

                ['Experience', 26, "What is your total experience in behaviour training?", "1-2 years, 2+ years behaviour specialist.", 10, 0, 0, 't_behav_experience', 'behavioral_training', 'Bad:No experience,Poor:Assisted only,Average:3–6 months,Good:1–2 years,Excellent:2+ years behaviour specialist'],
                ['Experience', 26, "Which behaviour problems can you correct?", "Moderate issues (fear, barking), full behavior correction (aggression, anxiety).", 8, 0, 0, 't_behav_problems_correct', 'behavioral_training', 'Bad:None,Poor:Only barking,Average:Basic issues (jumping, pulling),Good:Moderate issues (fear, leash pulling, barking),Excellent:Full behaviour correction (aggression, anxiety, reactivity)'],
                ['Experience', 26, "What tools or methods do you use for behaviour training?", "Positive reinforcement + leash control, structured behaviour modification.", 8, 0, 0, 't_behav_tools_methods', 'behavioral_training', 'Bad:Force tools,Poor:Only verbal correction,Average:Basic treats,Good:Positive reinforcement + leash control,Excellent:Structured behaviour modification system (reward + desensitization + counter-conditioning)'],

                ['Situational Handling & Safety', 22, "If a dog attacks another dog during session, what will you do?", "Controlled separation, professional conflict de-escalation.", 8, 0, 0, 't_behav_dog_attack', 'behavioral_training', 'Bad:Hit dogs,Poor:Panic,Average:Separate them randomly,Good:Controlled separation + safety handling,Excellent:Professional conflict de-escalation + prevention protocol'],
                ['Situational Handling & Safety', 22, "How do you ensure safety during behaviour training?", "Controlled environment, structured safety-first behavioural protocol.", 8, 0, 0, 't_behav_safety', 'behavioral_training', 'Bad:No safety awareness,Poor:Basic caution,Average:Normal supervision,Good:Controlled environment,Excellent:Structured safety-first behavioural protocol'],
                ['Situational Handling & Safety', 22, "What do you do if the dog refuses to respond to commands?", "Change approach gradually, modify training plan based on analysis.", 6, 0, 0, 't_behav_command_refusal', 'behavioral_training', 'Bad:Get angry,Poor:Stop training,Average:Repeat same method,Good:Change approach gradually,Excellent:Modify training plan based on behaviour analysis'],

                ['Discipline & Professional Behaviour', 8, "If you get a phone call during training, what will you do?", "Avoid calls, full focus on session.", 4, 0, 0, 't_behav_phone_distraction', 'behavioral_training', 'Bad:Attend call,Poor:Short call,Average:Ignore sometimes,Good:Avoid calls,Excellent:Full focus on session'],
                ['Discipline & Professional Behaviour', 8, "What risks of using phone during behaviour training?", "Loss of control, safety + aggression risk clearly explained.", 4, 0, 0, 't_behav_phone_risks', 'behavioral_training', 'Bad:No risk,Poor:Minor distraction,Average:Loss of control,Good:Behaviour escalation risk,Excellent:Safety + aggression risk clearly explained'],

                ['Client Interaction', 6, "How will you handle a pet parent who does not follow behaviour training instructions?", "Politely guide again, structured owner training + strict guidance.", 6, 0, 0, 't_behav_owner_noncompliance', 'behavioral_training', 'Bad:Stop training,Poor:Argue,Average:Continue without correction,Good:Politely guide again,Excellent:Structured owner training + strict behavioural guidance plan'],

                // --- Advanced Training ---
                ['Behaviour, Psychology & Training Expertise', 27, "Why did you choose advanced dog training as your specialization?", "Interested in working dog performance, passionate about professional-level discipline.", 5, 0, 0, 't_adv_why_specialization', 'advanced_training', 'Bad:For money only,Poor:General interest in dogs,Average:Likes training dogs,Good:Interested in working dog performance,Excellent:Passionate about professional-level working dog training & discipline'],
                ['Behaviour, Psychology & Training Expertise', 27, "How do you approach training a protection/guard dog?", "Controlled obedience + discipline, structured working dog training with safety-first.", 8, 0, 0, 't_adv_guard_approach', 'advanced_training', 'Bad:Use aggression/force,Poor:No clear method,Average:Basic obedience first,Good:Controlled obedience + discipline building,Excellent:Structured working dog training with control, obedience, and safety-first method'],
                ['Behaviour, Psychology & Training Expertise', 27, "What is your understanding of tracking/scent training?", "Scent introduction + tracking drills, structured scent discrimination system.", 8, 0, 0, 't_adv_scent_understanding', 'advanced_training', 'Bad:No idea,Poor:Heard about it only,Average:Basic understanding,Good:Uses scent introduction + basic tracking drills,Excellent:Structured scent discrimination + tracking progression training system'],
                ['Behaviour, Psychology & Training Expertise', 27, "How do you ensure control in high-drive or aggressive working dogs?", "Leash control + obedience discipline, advanced control system with structured commands.", 6, 0, 0, 't_adv_high_drive_control', 'advanced_training', 'Bad:Cannot handle,Poor:Avoid such dogs,Average:Basic control only,Good:Leash control + obedience discipline,Excellent:Advanced control system with structured commands and handler focus training'],

                ['Experience', 34, "What is your total experience in advanced dog training?", "2–3 years working dogs, 3+ years advanced/protection/scent training.", 10, 0, 0, 't_adv_experience', 'advanced_training', 'Bad:No experience,Poor:Assisted only,Average:1 year basic training,Good:2–3 years working dogs,Excellent:3+ years advanced/protection/scent training'],
                ['Experience', 34, "What types of advanced dogs have you trained?", "Guard dogs / working breeds, security, patrol, tracking-trained dogs.", 8, 0, 0, 't_adv_dog_types', 'advanced_training', 'Bad:None,Poor:Only pets,Average:Basic obedience dogs,Good:Guard dogs / working breeds,Excellent:Security dogs, patrol dogs, tracking-trained dogs'],
                ['Experience', 34, "What commands do you use in advanced training?", "Obedience + control commands, full working commands.", 8, 0, 0, 't_adv_commands', 'advanced_training', 'Bad:Only sit/stay,Poor:Basic obedience,Average:Few control commands,Good:Obedience + control commands,Excellent:Full working commands (recall, hold, release, heel, search, track, guard control basics)'],
                ['Experience', 34, "What training tools do you use in advanced training?", "Harness + reward system, professional working dog training setup.", 8, 0, 0, 't_adv_tools', 'advanced_training', 'Bad:Force tools,Poor:Basic leash only,Average:Collar + leash,Good:Training leash + harness + reward system,Excellent:Professional working dog training setup (long leash, harness, scent tools, reward system, controlled gear)'],

                ['Situational Safety & Control', 22, "If a trained protection dog becomes uncontrolled, what will you do?", "Use control commands + distance management, structured de-escalation protocol.", 8, 0, 0, 't_adv_uncontrolled_handling', 'advanced_training', 'Bad:Hit dog,Poor:Panic,Average:Try to stop randomly,Good:Use control commands + distance management,Excellent:Structured de-escalation + handler control protocol'],
                ['Situational Safety & Control', 22, "How do you ensure safety during protection/tracking training?", "Controlled environment, strict safety protocol + structured progression.", 8, 0, 0, 't_adv_safety', 'advanced_training', 'Bad:No safety awareness,Poor:Basic caution,Average:Normal supervision,Good:Controlled environment training,Excellent:Strict safety protocol + structured training environment + controlled progression'],
                ['Situational Safety & Control', 22, "What do you do if a dog refuses to follow advanced commands?", "Adjust technique slowly, behaviour analysis + modification plan.", 6, 0, 0, 't_adv_command_refusal', 'advanced_training', 'Bad:Get angry,Poor:Stop training,Average:Repeat same method,Good:Adjust technique slowly,Excellent:Behaviour analysis + training modification plan'],

                ['Discipline & Professional Behaviour', 8, "If you get a phone call during advanced training, what will you do?", "Avoid calls, full focus on working dog session.", 4, 0, 0, 't_adv_phone_distraction', 'advanced_training', 'Bad:Attend call,Poor:Short call,Average:Ignore sometimes,Good:Avoid calls during training,Excellent:Full focus on working dog session'],
                ['Discipline & Professional Behaviour', 8, "What risks if phone is used during advanced training?", "Loss of focus/control risk, high safety risk clearly explained.", 4, 0, 0, 't_adv_phone_risks', 'advanced_training', 'Bad:No risk,Poor:Minor distraction,Average:Loss of focus,Good:Control loss risk,Excellent:High safety risk (dog control + aggression escalation clearly explained)'],

                ['Client Interaction', 12, "How do you explain advanced training to pet parents?", "Clear explanation, structured training plan + real-world application.", 6, 0, 0, 't_adv_parent_explanation', 'advanced_training', 'Bad:No explanation,Poor:Confusing explanation,Average:Basic explanation,Good:Clear explanation,Excellent:Structured training plan + real-world application explanation'],
                ['Client Interaction', 12, "If a pet parent misuses protection training commands, what will you do?", "Warn and guide properly, strict correction + safety education.", 6, 0, 0, 't_adv_command_misuse', 'advanced_training', 'Bad:Ignore,Poor:Argue,Average:Continue anyway,Good:Warn and guide properly,Excellent:Strict correction + responsible usage training + safety education'],
                         ];
=======
                ['Behaviour, Psychology & Training Approach', 30, "Why did you choose to become a dog trainer?", "Love for dogs, interest in behaviour, helping dogs and pet parents.", 3, 0, 0, 't_why_trainer'],
                ['Behaviour, Psychology & Training Approach', 30, "What training method do you follow and why?", "Positive, reward-based, no force. Builds trust and better learning.", 6, 0, 0, 't_method'],
                ['Behaviour, Psychology & Training Approach', 30, "How do you handle a dog that shows aggression?", "Stay calm, find the reason, correct slowly without force.", 5, 0, 0, 't_handle_aggression'],
                ['Behaviour, Psychology & Training Approach', 30, "How do you gain a new dog’s trust in the first session?", "Slow approach, soft voice, let the dog sniff, use treats.", 4, 0, 0, 't_gain_trust'],
                ['Behaviour, Psychology & Training Approach', 30, "What signs show a dog is stressed during training?", "Lip licking, yawning, tail down, avoiding eye contact, whining.", 4, 0, 0, 't_stress_signs'],
                ['Behaviour, Psychology & Training Approach', 30, "What should a trainer never do during training and why?", "Never hit, shout, scare, or pull hard. It breaks trust.", 4, 0, 0, 't_never_do'],
                ['Behaviour, Psychology & Training Approach', 30, "How do you make training enjoyable for the dog?", "Short sessions, rewards, play time, patience.", 4, 0, 0, 't_make_fun'],
                
                ['Training Experience & Qualification', 20, "Explain your practical experience in training dogs.", "Hands-on work with different dogs and behaviour cases.", 8, 1, 0, 't_exp_detail'],
                ['Training Experience & Qualification', 20, "How many years of experience do you have?", "Clear timeline and types of dogs handled.", 5, 0, 1, 't_years_exp'],
                ['Training Experience & Qualification', 20, "What types of training have you given?", "Obedience, potty, leash, socialization, behaviour correction, aggression.", 4, 0, 0, 't_services_list'],
                ['Training Experience & Qualification', 20, "Do you have any dog training certificate? From where?", "Course or certification details.", 3, 0, 0, 't_certification'],
                
                ['Training Knowledge & Session Structure', 15, "How do you plan a training session for a new dog?", "Check behaviour -> set goal -> step-by-step training.", 5, 0, 0, 't_plan_session'],
                ['Training Knowledge & Session Structure', 15, "How long should one training session be and why?", "20–40 minutes so the dog does not get stressed.", 3, 0, 0, 't_session_duration'],
                ['Training Knowledge & Session Structure', 15, "What is the role of the pet parent in the dog’s training?", "Follow advice, practice daily, be consistent at home.", 4, 0, 0, 't_parent_role'],
                ['Training Knowledge & Session Structure', 15, "How many sessions are needed for basic obedience and why?", "Around 8–15 sessions depending on dog and practice.", 3, 0, 0, 't_session_count'],
                
                ['Situational Handling & Safety', 15, "If a dog tries to bite you during training, what will you do?", "Stay calm, step back, understand the reason.", 3, 0, 0, 't_bite_action'],
                ['Situational Handling & Safety', 15, "If the dog runs away during outdoor training, what will you do?", "Secure safely, call calmly, inform parent/team.", 2, 0, 0, 't_run_away_action'],
                ['Situational Handling & Safety', 15, "If the dog gets injured during training, what will you do?", "Inform pet parent and Petsfolio, get medical help.", 3, 0, 0, 't_injury_action'],
                ['Situational Handling & Safety', 15, "What precautions do you take before leash/outdoor training?", "Proper leash, safe place, check surroundings.", 3, 0, 0, 't_outdoor_precautions'],
                ['Situational Handling & Safety', 15, "How do you make sure the dog does not get over-stressed?", "Watch body language, give breaks.", 4, 0, 0, 't_prevent_stress'],
                
                ['Job Commitment & Platform Discipline', 10, "Is this full-time or part-time for you? How serious are you?", "Serious and regular commitment.", 5, 0, 0, 't_commitment'],
                ['Job Commitment & Platform Discipline', 10, "Why is it important to follow Petsfolio rules and policies?", "For safety, trust, and proper service.", 5, 0, 0, 't_follow_rules'],
                
                ['Client Interaction & Professionalism', 7, "How will you handle a pet parent who wants fast results?", "Explain calmly and set real expectations.", 2, 0, 0, 't_rush_request'],
                ['Client Interaction & Professionalism', 7, "If a client asks you to use harsh methods, what will you do?", "Politely refuse and explain correct method.", 2, 0, 0, 't_harsh_method'],
                ['Client Interaction & Professionalism', 7, "If a client asks you to do extra session or handle another pet not booked, what will you do?", "Ask them to book through the Petsfolio app.", 2, 0, 0, 't_extra_work'],
                ['Client Interaction & Professionalism', 7, "What dress code and behaviour will you maintain?", "Clean clothes, proper shoes, polite behaviour.", 1, 0, 0, 't_dress_code'],
                
                ['Trustworthiness, Ethics & Real Experience', 13, "Why should a client trust you with their home and pet?", "Responsible, professional, respect safety.", 3, 0, 0, 't_trust_reason'],
                ['Trustworthiness, Ethics & Real Experience', 13, "Explain how you follow safe and ethical training at client homes?", "No force, safety first, respect house rules.", 3, 0, 0, 't_home_practice'],
                ['Trustworthiness, Ethics & Real Experience', 13, "Why is police/background verification important for this job?", "Builds trust and ensures safety.", 3, 0, 0, 't_verification'],
                ['Trustworthiness, Ethics & Real Experience', 13, "Tell me about one difficult training case you handled.", "Real example, calm handling, correct method.", 2, 0, 0, 't_case_study'],
                ['Trustworthiness, Ethics & Real Experience', 13, "Tell me about a time when a dog was not listening. What did you change?", "Changed method, patience, different approach.", 1, 0, 0, 't_non_coop'],
                ['Trustworthiness, Ethics & Real Experience', 13, "Tell me about a time when you corrected a pet parent. How did you explain?", "Politely educated, no argument.", 1, 0, 0, 't_parent_educate'],
                ['Ethics & Platform Discipline', 0, "Do you understand that taking clients offline will result in a penalty and account block?", "Instruction to be read by Executive: Taking clients offline is strictly not allowed.", 0, 0, 0, 't_offline_warning'],
            ];
>>>>>>> origin/main

            foreach ($training as $idx => $q) {
                $seedData[] = [
                    'service_id'      => 3,
                    'category_name'   => $q[0],
                    'category_weight' => $q[1],
                    'question_text'   => $q[2],
                    'hint_text'       => $q[3],
<<<<<<< HEAD
                    'weight'          => $q[4],
                    'is_trigger'      => $q[5],
                    'is_dependent'    => $q[6],
                    'field_name'      => $q[7],
                    'training_type'   => $q[8] ?? null,
                    'options'         => $q[9] ?? $commonOptions,
                    'sort_order'      => $idx + 1,
                    'created_at'      => date('Y-m-d H:i:s'),
                    'updated_at'      => date('Y-m-d H:i:s'),
                ];
            }

            // --- SITTING (ID 6) ---
            $sitting = [
                ['Basic Details', 10, "Do you have experience in pet sitting before? Explain.", "Expected: Real past experience with dogs/cats, what kind of care they gave.", 2, 0, 0, 's_sitting_experience'],
                ['Basic Details', 10, "Are you comfortable handling both dogs and cats?", "Expected: Yes, confident with both.", 2, 0, 0, 's_dogs_cats_comfort'],
                ['Basic Details', 10, "Do you have any fear of pets?", "Expected: No fear, calm with pets.", 2, 0, 0, 's_fear_of_pets'],
                ['Basic Details', 10, "Can you do home visits and stay for long hours if needed?", "Expected: Yes, flexible for visits/stays.", 2, 0, 0, 's_home_visits'],
                ['Basic Details', 10, "Do you have any pet sitting certificate or training?", "Expected: If yes, explain. If no, at least practical experience.", 2, 0, 0, 's_sitting_certificate'],

                ['Food & Medication Knowledge', 15, "How will you follow food instructions given by the pet parent?", "Expected: Follow exact timing and quantity.", 5, 0, 0, 's_food_instructions'],
                ['Food & Medication Knowledge', 15, "What will you do if the pet refuses to eat?", "Expected: Inform pet parent, try calmly, don’t force.", 5, 0, 0, 's_refuse_to_eat'],
                ['Food & Medication Knowledge', 15, "How will you give medicine if required?", "Expected: As per instruction, safely and calmly.", 5, 0, 0, 's_give_medicine'],

                ['Walking & Handling', 15, "How will you handle the dog during a walk?", "Expected: Proper leash control, alert to surroundings.", 5, 0, 0, 's_dog_handling_walk'],
                ['Walking & Handling', 15, "If the dog suddenly runs away, what will you do?", "Expected: Stay calm, call the dog, secure safely.", 5, 0, 0, 's_dog_run_away'],
                ['Walking & Handling', 15, "If another dog tries to attack, what will you do?", "Expected: Move away quickly, protect the dog calmly.", 5, 0, 0, 's_dog_attack'],

                ['Emergency Handling', 15, "If the pet gets injured or sick during service, what will you do?", "Expected: Inform pet parent and Petsfolio immediately, take to vet if needed.", 7.5, 0, 0, 's_pet_injured'],
                ['Emergency Handling', 15, "If the pet parent is not reachable in emergency, what will you do?", "Expected: Contact Petsfolio team and act for pet safety.", 7.5, 0, 0, 's_parent_unreachable'],

                ['Home Responsibility', 10, "How will you behave inside the client’s home?", "Expected: Respectful, no touching personal items.", 5, 0, 0, 's_home_behaviour'],
                ['Home Responsibility', 10, "Will you send photo/video updates to pet parent?", "Expected: Yes, regular updates.", 5, 0, 0, 's_send_updates'],

                ['Real Experience Scenarios', 15, "Tell me about one difficult pet sitting case you handled.", "Expected: Real example, calm handling.", 7.5, 0, 0, 's_difficult_sitting_case'],
                ['Real Experience Scenarios', 15, "Tell me about a time a pet was not listening. What did you do?", "Expected: Patience, smart handling.", 7.5, 0, 0, 's_pet_not_listening'],

                ['Trustworthiness, Ethics & Professionalism', 20, "If a client asks you to do extra work or handle another pet not booked, what will you do?", "Expected: Politely ask them to book through the Petsfolio app.", 7, 0, 0, 's_extra_work_sitting'],
                ['Trustworthiness, Ethics & Professionalism', 20, "Tell me about a time you handled a misunderstanding with a pet parent.", "Expected: Calm explanation, no arguments.", 7, 0, 0, 's_parent_misunderstanding'],
                ['Trustworthiness, Ethics & Professionalism', 20, "Why is honesty important in pet sitting?", "Expected: Trust, safety, responsibility.", 6, 0, 0, 's_sitting_honesty'],

                ['Ethics & Platform Discipline', 0, "Do you understand that taking clients offline will result in a penalty and account block?", "Instruction to be read by Executive: Taking clients outside the Petsfolio platform is strictly not allowed. If you take clients offline, your account will be blocked, you will have to pay a penalty, and you will not receive any more leads. All services must be accepted only through proper bookings in the app.", 0, 0, 0, 's_offline_warning'],
            ];

            foreach ($sitting as $idx => $q) {
                $seedData[] = [
                    'service_id'      => 6,
                    'category_name'   => $q[0],
                    'category_weight' => $q[1],
                    'question_text'   => $q[2],
                    'hint_text'       => $q[3],
                    'weight'          => $q[4],
                    'is_trigger'      => $q[5],
                    'is_dependent'    => $q[6],
                    'field_name'      => $q[7],
                    'training_type'   => null,
                    'options'         => $commonOptions,
=======
                    'options'         => $commonOptions,
                    'weight'          => $q[4],
                    'is_trigger'      => $q[5],
                    'is_dependent'    => $q[6],
                    'field_name'      => $q[7],
>>>>>>> origin/main
                    'sort_order'      => $idx + 1,
                    'created_at'      => date('Y-m-d H:i:s'),
                    'updated_at'      => date('Y-m-d H:i:s'),
                ];
            }

            $db->table('verification_questions')->insertBatch($seedData);
        }
    }
}
