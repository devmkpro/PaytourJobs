<?php

use App\Models\Candidates;
use App\Enums\EducationLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;

uses(RefreshDatabase::class);

describe('Candidate Validations', function () {
    
    it('validates name is required', function () {
        $data = [
            'email' => 'test@example.com',
            'phone' => '(11) 99999-9999',
            'desired_position' => 'Developer',
            'education_level' => EducationLevel::SUPERIOR->value,
        ];
        
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|min:8|max:20',
            'desired_position' => 'required|string|max:255',
            'education_level' => 'required|string',
        ]);
        
        expect($validator->fails())->toBeTrue()
            ->and($validator->errors()->has('name'))->toBeTrue();
    });

    it('validates name maximum length', function () {
        $data = [
            'name' => str_repeat('a', 256), // 256 characters
            'email' => 'test@example.com',
            'phone' => '(11) 99999-9999',
            'desired_position' => 'Developer',
            'education_level' => EducationLevel::SUPERIOR->value,
        ];
        
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|min:8|max:20',
            'desired_position' => 'required|string|max:255',
            'education_level' => 'required|string',
        ]);
        
        expect($validator->fails())->toBeTrue()
            ->and($validator->errors()->has('name'))->toBeTrue();
    });

    it('validates email format', function () {
        $invalidEmails = [
            'invalid-email',
            'test@',
            '@example.com',
            'test.example.com',
            'test@.com',
        ];
        
        foreach ($invalidEmails as $email) {
            $data = [
                'name' => 'Test User',
                'email' => $email,
                'phone' => '(11) 99999-9999',
                'desired_position' => 'Developer',
                'education_level' => EducationLevel::SUPERIOR->value,
            ];
            
            $validator = Validator::make($data, [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|min:8|max:20',
                'desired_position' => 'required|string|max:255',
                'education_level' => 'required|string',
            ]);
            
            expect($validator->fails())->toBeTrue("Failed for email: {$email}")
                ->and($validator->errors()->has('email'))->toBeTrue("Failed for email: {$email}");
        }
    });

    it('accepts valid email formats', function () {
        $validEmails = [
            'test@example.com',
            'user.name@domain.co.uk',
            'test+tag@example.org',
            'user123@test-domain.com',
        ];
        
        foreach ($validEmails as $email) {
            $data = [
                'name' => 'Test User',
                'email' => $email,
                'phone' => '(11) 99999-9999',
                'desired_position' => 'Developer',
                'education_level' => EducationLevel::SUPERIOR->value,
            ];
            
            $validator = Validator::make($data, [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|min:8|max:20',
                'desired_position' => 'required|string|max:255',
                'education_level' => 'required|string',
            ]);
            
            expect($validator->passes())->toBeTrue("Failed for email: {$email}");
        }
    });

    it('validates phone minimum length', function () {
        $shortPhones = ['123', '1234', '12345', '123456', '1234567']; // All less than 8 characters
        
        foreach ($shortPhones as $phone) {
            $data = [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'phone' => $phone,
                'desired_position' => 'Developer',
                'education_level' => EducationLevel::SUPERIOR->value,
            ];
            
            $validator = Validator::make($data, [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|min:8|max:20',
                'desired_position' => 'required|string|max:255',
                'education_level' => 'required|string',
            ]);
            
            expect($validator->fails())->toBeTrue("Failed for phone: {$phone}")
                ->and($validator->errors()->has('phone'))->toBeTrue("Failed for phone: {$phone}");
        }
    });

    it('validates phone maximum length', function () {
        $longPhone = str_repeat('1', 21); // 21 characters
        
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => $longPhone,
            'desired_position' => 'Developer',
            'education_level' => EducationLevel::SUPERIOR->value,
        ];
        
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|min:8|max:20',
            'desired_position' => 'required|string|max:255',
            'education_level' => 'required|string',
        ]);
        
        expect($validator->fails())->toBeTrue()
            ->and($validator->errors()->has('phone'))->toBeTrue();
    });

    it('accepts valid phone formats', function () {
        $validPhones = [
            '(11) 99999-9999',
            '(21) 98888-8888',
            '11999999999',
            '(11) 9999-9999',
        ];
        
        foreach ($validPhones as $phone) {
            $data = [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'phone' => $phone,
                'desired_position' => 'Developer',
                'education_level' => EducationLevel::SUPERIOR->value,
            ];
            
            $validator = Validator::make($data, [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|min:8|max:20',
                'desired_position' => 'required|string|max:255',
                'education_level' => 'required|string',
            ]);
            
            expect($validator->passes())->toBeTrue("Failed for phone: {$phone}");
        }
    });

    it('validates desired position is required', function () {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '(11) 99999-9999',
            'education_level' => EducationLevel::SUPERIOR->value,
        ];
        
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|min:8|max:20',
            'desired_position' => 'required|string|max:255',
            'education_level' => 'required|string',
        ]);
        
        expect($validator->fails())->toBeTrue()
            ->and($validator->errors()->has('desired_position'))->toBeTrue();
    });

    it('validates education level is required', function () {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '(11) 99999-9999',
            'desired_position' => 'Developer',
        ];
        
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|min:8|max:20',
            'desired_position' => 'required|string|max:255',
            'education_level' => 'required|string',
        ]);
        
        expect($validator->fails())->toBeTrue()
            ->and($validator->errors()->has('education_level'))->toBeTrue();
    });

    it('accepts valid education level values', function () {
        $validLevels = [
            EducationLevel::FUNDAMENTAL->value,
            EducationLevel::MEDIO->value,
            EducationLevel::SUPERIOR->value,
            EducationLevel::POS_GRADUACAO->value,
        ];
        
        foreach ($validLevels as $level) {
            $data = [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'phone' => '(11) 99999-9999',
                'desired_position' => 'Developer',
                'education_level' => $level,
            ];
            
            $validator = Validator::make($data, [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|min:8|max:20',
                'desired_position' => 'required|string|max:255',
                'education_level' => 'required|string',
            ]);
            
            expect($validator->passes())->toBeTrue("Failed for education level: {$level}");
        }
    });

    it('validates observations maximum length', function () {
        $longObservations = str_repeat('a', 1001); // 1001 characters
        
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '(11) 99999-9999',
            'desired_position' => 'Developer',
            'education_level' => EducationLevel::SUPERIOR->value,
            'observations' => $longObservations,
        ];
        
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|min:8|max:20',
            'desired_position' => 'required|string|max:255',
            'education_level' => 'required|string',
            'observations' => 'nullable|string|max:1000',
        ]);
        
        expect($validator->fails())->toBeTrue()
            ->and($validator->errors()->has('observations'))->toBeTrue();
    });

    it('allows null observations', function () {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '(11) 99999-9999',
            'desired_position' => 'Developer',
            'education_level' => EducationLevel::SUPERIOR->value,
            'observations' => null,
        ];
        
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|min:8|max:20',
            'desired_position' => 'required|string|max:255',
            'education_level' => 'required|string',
            'observations' => 'nullable|string|max:1000',
        ]);
        
        expect($validator->passes())->toBeTrue();
    });

    it('validates file mime types', function () {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '(11) 99999-9999',
            'desired_position' => 'Developer',
            'education_level' => EducationLevel::SUPERIOR->value,
        ];
        
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|min:8|max:20',
            'desired_position' => 'required|string|max:255',
            'education_level' => 'required|string',
            'resume_path' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);
        
        expect($validator->passes())->toBeTrue();
    });
});
