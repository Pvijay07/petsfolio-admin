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
        if (!$db->tableExists('verification_questions') || $db->table('verification_questions')->countAll() < 90) {
            $this->runInitialSetup();
        }

        $data['title'] = 'Verification Questions';
        $data['page']  = 'verification-questions';
        
        $services = $this->serviceModel->where('status', 'Active')->get()->getResultArray();
        $questions = $this->questionModel->orderBy('sort_order', 'ASC')->get()->getResultArray();
        
        $groupedQuestions = [];
        foreach ($questions as $q) {
            $groupedQuestions[$q['service_id']][$q['category_name']][] = $q;
        }

        $data['services'] = $services;
        $data['groupedQuestions'] = $groupedQuestions;
        
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
            'group_key'       => $this->request->getPost('group_key'),
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

        // Create table if not exists with options column
        $fields = [
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'service_id' => ['type' => 'INT', 'constraint' => 11],
            'category_name' => ['type' => 'VARCHAR', 'constraint' => 255],
            'category_weight' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0.00],
            'question_text' => ['type' => 'TEXT'],
            'hint_text' => ['type' => 'TEXT', 'null' => true],
            'options' => ['type' => 'TEXT', 'null' => true], // New column for dropdowns
            'weight' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0.00],
            'is_trigger' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'is_dependent' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'sort_order' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'status' => ['type' => 'ENUM', 'constraint' => ['active', 'inactive'], 'default' => 'active'],
            'field_name' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
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

        // Seed if empty or incomplete (Increased threshold to 90 to ensure full list)
        if ($db->table('verification_questions')->countAll() < 90) {
            $db->table('verification_questions')->truncate();
            $seedData = [];
            $commonOptions = "Bad,Poor,Average,Good,Excellent";
            
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
                
                ['Experience', 20, "Do you have experience handling dogs?", "Yes, I have practical experience handling dogs.", 8, 1, 0, 'q_has_exp'],
                ['Experience', 20, "How many years of experience do you have with dogs?", "Clear hands-on experience explanation with confidence.", 7, 0, 1, 'q_years_exp'],
                ['Experience', 20, "Do you own or have you owned a pet?", "Yes, I understand pet behaviour and responsibilities.", 5, 0, 0, 'q_own_pet'],
                
                ['Job Commitment', 15, "Is this full-time or part-time for you? How serious are you about this work?", "I am serious about this work and can commit consistently.", 8, 0, 0, 'q_commitment'],
                ['Job Commitment', 15, "Do you agree to follow Petsfolio rules and not take clients offline?", "Yes, I will strictly follow Petsfolio policies.", 7, 0, 0, 'q_follow_rules'],
                
                ['Situational Safety & Presence of Mind', 15, "If the dog suddenly runs away during a walk, what will you do?", "I will stay calm, safely try to stop/call the dog, and immediately inform the client and team if needed.", 3, 0, 0, 'q_run_away_action'],
                ['Situational Safety & Presence of Mind', 15, "What precautions will you take to ensure the dog does NOT run away during a walk?", "Proper leash grip, staying alert, avoiding distractions, and choosing safe routes.", 3, 0, 0, 'q_prevent_run_away'],
                ['Situational Safety & Presence of Mind', 15, "If another dog tries to attack the dog you are handling, what will you do?", "I will calmly move away, protect the dog, and avoid panic.", 2, 0, 0, 'q_dog_attack_action'],
                ['Situational Safety & Presence of Mind', 15, "What precautions will you take to avoid fights or attacks from other dogs during a walk?", "I will maintain distance from unknown dogs and stay aware of surroundings.", 2, 0, 0, 'q_prevent_attacks'],
                ['Situational Safety & Presence of Mind', 15, "If the dog gets injured or meets with an accident during service, what will you do?", "I will immediately inform the pet parent and Petsfolio team and seek medical help if required.", 2, 0, 0, 'q_injury_action'],
                ['Situational Safety & Presence of Mind', 15, "What precautions will you take to avoid accidents or injuries during service?", "I will avoid risky areas, stay attentive, and ensure safe handling throughout the walk.", 2, 0, 0, 'q_prevent_accidents'],
                ['Situational Safety & Presence of Mind', 15, "If the dog suddenly becomes sick or unwell during service, what will you do?", "I will stop the service, inform the parent/team immediately, and seek medical support.", 1, 0, 0, 'q_sickness_action'],
                
                ['Punctuality & Travel', 10, "Will you be punctual for every service?", "Yes, I understand punctuality is important.", 4, 0, 0, 'q_punctuality'],
                ['Punctuality & Travel', 10, "If you are getting late for a service, what will you do?", "I will inform the client and Petsfolio team in advance.", 3, 0, 0, 'q_late_action'],
                ['Punctuality & Travel', 10, "How far can you travel for services?", "Flexible and realistic travel commitment.", 1, 0, 0, 'q_travel_limit'],
                ['Punctuality & Travel', 10, "What is your mode of transportation?", "Own vehicle or reliable transportation method.", 2, 0, 0, 'q_transport'],
                
                ['Mobile Usage Discipline', 5, "If you receive a phone call while walking a dog, what will you do?", "I will avoid attending the call and focus on the dog’s safety.", 3, 0, 0, 'q_phone_call'],
                ['Mobile Usage Discipline', 5, "What risks can happen if you use the phone while walking a dog?", "The dog may run away, eat something dangerous, pull suddenly, or get injured.", 2, 0, 0, 'q_phone_risk'],
                
                ['Human Behaviour with Clients', 5, "How will you handle a difficult or upset pet parent during a service?", "I will stay calm, polite, avoid arguments, and inform the Petsfolio team if required.", 2, 0, 0, 'q_difficult_parent'],
                ['Human Behaviour with Clients', 5, "If a client asks you to do extra work or handle another pet not booked, what will you do?", "I will politely ask them to book through the Petsfolio app.", 2, 0, 0, 'q_extra_work'],
                ['Human Behaviour with Clients', 5, "What kind of dress code will you follow while going for a service?", "Clean, comfortable clothes and proper footwear suitable for pet handling.", 1, 0, 0, 'q_dress_code'],
                
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
                    'sort_order'      => $idx + 1,
                    'created_at'      => date('Y-m-d H:i:s'),
                    'updated_at'      => date('Y-m-d H:i:s'),
                ];
            }

            // --- GROOMING (ID 2) ---
            $grooming = [
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
            ];

            foreach ($grooming as $idx => $q) {
                $seedData[] = [
                    'service_id'      => 2,
                    'category_name'   => $q[0],
                    'category_weight' => $q[1],
                    'question_text'   => $q[2],
                    'hint_text'       => $q[3],
                    'options'         => $commonOptions,
                    'weight'          => $q[4],
                    'is_trigger'      => $q[5],
                    'is_dependent'    => $q[6],
                    'field_name'      => $q[7],
                    'sort_order'      => $idx + 1,
                    'created_at'      => date('Y-m-d H:i:s'),
                    'updated_at'      => date('Y-m-d H:i:s'),
                ];
            }

            // --- TRAINING (ID 3) ---
            $training = [
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

            foreach ($training as $idx => $q) {
                $seedData[] = [
                    'service_id'      => 3,
                    'category_name'   => $q[0],
                    'category_weight' => $q[1],
                    'question_text'   => $q[2],
                    'hint_text'       => $q[3],
                    'options'         => $commonOptions,
                    'weight'          => $q[4],
                    'is_trigger'      => $q[5],
                    'is_dependent'    => $q[6],
                    'field_name'      => $q[7],
                    'sort_order'      => $idx + 1,
                    'created_at'      => date('Y-m-d H:i:s'),
                    'updated_at'      => date('Y-m-d H:i:s'),
                ];
            }

            $db->table('verification_questions')->insertBatch($seedData);
        }
    }
}
