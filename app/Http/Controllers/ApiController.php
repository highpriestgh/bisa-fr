<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use JD\Cloudder\Facades\Cloudder;
use App\Helpers\CustomSMS;
use App\Helpers\CustomMailer;
use App\Helpers\HelperFunctions;

use App\Models\User;
use App\Models\Admin;
use App\Models\Doctor;
use App\Models\Article;
use App\Models\Question;
use App\Models\UserHealthInfo;
use App\Models\ArticleCategory;
use App\Models\QuestionCategory;
use App\Models\QuestionResponse;
use App\Models\HealthResource;
use App\Models\Pharmacy;
use App\Models\Video;
use App\Models\SmsVerficication;
use App\Models\PasswordRequest;

class ApiController extends Controller
{
    /**
     * Create new user account
     */
    public function createUserAccount(Request $request)
    {
        if (User::where('email', $request->email)->exists() || Doctor::where('email', $request->email)->exists() || Admin::where('admin_email', $request->email)->exists()) {
            $response_message =  array('success' => false, 'message' => "Email already taken");
            return response()->json($response_message);
        } else {
            if (User::where('phone', $request->phone)->exists()) {
                $response_message =  array('success' => false, 'message' => "Phone number already taken");
                return response()->json($response_message);
            } else {
                $hashedPassword = password_hash($request->password, PASSWORD_DEFAULT);
                $user = new User();
                $userToken = substr(md5(time()),0, 20);
                $username = substr($request->firstName, 0, 3).substr($request->lastName,0,3)."_".substr(md5(time() + rand()), 0, 4);

                $user->first_name = $request->firstName;
                $user->last_name = $request->lastName;
                $user->username = $username;
                $user->phone = $request->phone;
                $user->email = $request->email;
                $user->password = $hashedPassword;
                $user->country = $request->country;
                $user->type = 'known';
                $user->active = 0;
                $user->token = $userToken;

                if ($user->save()) {
                    // generate code and send sms to user
                    $verificationCode = rand(1000, 9999);
                    $messageBody = "$verificationCode is your Bisa Fr activation code. Thank you for joining us.";
                    $customSMS = new CustomSMS();
                    $send_sms = $customSMS->sendSMS($request->phone, $messageBody);
                    if ($send_sms) {
                        $smsVerification = new SmsVerficication();
                        $smsVerification->uid = $user->user_id;
                        $smsVerification->phone_number = $request->phone;
                        $smsVerification->code = $verificationCode;
                        $smsVerification->save();

                        $response_message =  array('success' => true, 'message' => 'Signup successful',  'userType' => 'known', 'userToken'=> $userToken, 'userId' => $user->user_id, 'smsVerificationSent' => true, 'username'=> $username);
                        return response()->json($response_message);
                    }

                    $response_message =  array('success' => true, 'message' => 'Signup successful',  'userType' => 'known', 'userToken'=> $userToken, 'userId' => $user->user_id, 'smsVerificationSent' => false, 'username'=> $username );
                    return response()->json($response_message);

                } else {
                    $response_message =  array('success' => false, 'message' => 'Unable to signup.Please check internet connection');
                    return response()->json($response_message);
                }
            }

        }
    }


    /**
     * Create new anonymous user account
     */
    public function createAnonymousUserAccount(Request $request)
    {
        if (User::where('username', $request->username)->exists() || Doctor::where('username', $request->username)->exists() || Admin::where('admin_username', $request->username)->exists()) {
            $response_message =  array('success' => false, 'message' => "Username already taken");
            return response()->json($response_message);
        } else {
            $hashedPassword = password_hash($request->password, PASSWORD_DEFAULT);
            $user = new User();
            $userToken = substr(md5(time()),0, 20);

            $user->username = $request->username;
            $user->password = $hashedPassword;
            $user->token = $userToken;

            if ($user->save()) {
                $response_message =  array('success' => true, 'message' => 'Signup successful', 'userType' => 'anonymous', 'userToken'=> $userToken, 'userId' => $user->user_id, 'username'=> $request->username );
                return response()->json($response_message);
            } else {
                $response_message =  array('success' => false, 'message' => 'Unable to signup');
                return response()->json($response_message);
            }
        }
    }


    /**
     * Sign in user account
     */
    public function signInUserAccount(Request $request)
    {
        if (User::where('email', $request->email)->exists()) {
            $user = User::where('email', $request->email)->latest()->first();
            $hashedPassword = $user['password'];
            if (password_verify($request->password, $hashedPassword)) {

                $userType = $user['type'];
                $userToken = $user['token'];
                $userId = $user['user_id'];
                $username = $user['username'];

                $userCodeCheck = SmsVerficication::where('uid', $userId)->latest()->first();
                if ($userCodeCheck) {
                    if ($userCodeCheck->status == 'pending') {
                        $response_message =  array('success' => true, 'message' => 'Login successful', 'userType' => $userType, 'userToken'=> $userToken, 'userId' => $userId, 'PhoneVerified' => false, 'username' => $username);
                        return response()->json($response_message);
                    } else if ($userCodeCheck->status == 'verified') {
                        $response_message =  array('success' => true, 'message' => 'Login successful', 'userType' => $userType, 'userToken'=> $userToken, 'userId' => $userId, 'PhoneVerified' => true, 'username' => $username);
                        return response()->json($response_message);
                    }

                } else {
                    $response_message =  array('success' => true, 'message' => 'Login successful', 'userType' => $userType, 'userToken'=> $userToken, 'userId' => $userId, 'PhoneVerified' => false, 'username' => $username);
                    return response()->json($response_message);
                }
            } else {
                $response_message =  array('success' => false, 'message' => "Wrong username or password");
                return response()->json($response_message);
            }
        } else {
            $response_message =  array('success' => false, 'message' => "Wrong username or password");
            return response()->json($response_message);
        }
    }


    /**
     * Requests user verification code
     */
    public function requestUserVerificationCode(Request $request)
    {
        if (User::where('user_id', $request->userId)->exists()) {
            $userPhoneNumber = "";

            if ($request->phoneNumber) {
                $userPhoneNumber = $request->phoneNumber;
            } else {
                $userPhoneNumber = User::find($request->userId)['phone'];
            }

            $verificationCode = rand(1000, 9999);
            $messageBody = "Your Bisa Fr activation code is $verificationCode";
            $customSMS = new CustomSMS();
            $send_sms = $customSMS->sendSMS($userPhoneNumber, $messageBody);
            if ($send_sms) {
                $smsVerification = new SmsVerficication();
                $smsVerification->uid = $request->userId;
                $smsVerification->phone_number = $userPhoneNumber;
                $smsVerification->code = $verificationCode;

                if ($smsVerification->save()) {
                    $response_message =  array('success' => true, 'message' => 'SMS verification code sent' );
                    return response()->json($response_message);
                } else {
                    $response_message =  array('success' => false, 'message' => 'SMS verification code not sent. Wrong number or internet error' );
                    return response()->json($response_message);
                }
            }
        } else {
            $response_message =  array('success' => false, 'message' => "Unknown user");
            return response()->json($response_message);
        }
    }


    /**
     * verifies user code
     */
    public function verifyCode(Request $request)
    {
        $userCodeCheck = SmsVerficication::where('uid', $request->userId)->latest()->first();
        if ($userCodeCheck) {
            if ($userCodeCheck->status == 'verified') {
                $response_message =  array('success' => false, 'message' => "Code is already verified");
                return response()->json($response_message);
            } else {
                if ($request->verificationCode == $userCodeCheck->code) {
                    $verification = SmsVerficication::find($userCodeCheck->id);
                    $verification->status = 'verified';

                    if ($verification->save()) {

                        $user = User::find($request->userId);
                        $user->active = 1;
                        $user->save();

                        $response_message =  array('success' => true, 'message' => "Code Verified successfully");
                        return response()->json($response_message);
                    }
                } else {
                    $response_message =  array('success' => false, 'message' => "Wrong verification code");
                    return response()->json($response_message);
                }
            }

        } else {
            $response_message =  array('success' => false, 'message' => "No verification code requested");
            return response()->json($response_message);
        }
    }


    /**
     * Requests user password
     */
    public function requestPasswordReset(Request $request)
    {
        if (User::where('email', $request->email)->exists()) {
            $user = User::where('email', $request->email)->first();
            $userId = $user['user_id'];
            $username = $user['first_name'];

            $code = substr(md5(time() + rand()), 0, 30);
            $helperFunction = new HelperFunctions();
            $serverName = $helperFunction->getBaseUrl();
            $requestLink = $serverName."/reset-password?code=$code";
            $mailText = "<a href='$requestLink'>$requestLink</a>";

            $mailer = new CustomMailer();
            $mailer->sendPasswordResetEmail($request->email, $username, $requestLink);
            $passwordRequest = new PasswordRequest();
            $passwordRequest->uid = $userId;
            $passwordRequest->email = $request->email;
            $passwordRequest->code = $code;
            $passwordRequest->save();

            $response_message =  array('success' => true, 'message' => "A password reset link has been sent to $request->email");
            return response()->json($response_message);
        } else {
            $response_message =  array('success' => false, 'message' => "Email Address Does Not Exist");
            return response()->json($response_message);
        }
    }

    /**
     * Get user details
     */
    public function getUserDetails($userId) {
        $user = User::find($userId);
        if ($user) {
            if ($user->type == "anonymous") {
                $response_message =  array('success' => false, 'message' => "Anonymous user");
                return response()->json($response_message);
            } else {
                $userData = array();

                $userHealthInfo = UserHealthInfo::where('uid', $userId)->first();

                $userData['firstName'] = $user->first_name;
                $userData['lastName'] = $user->last_name;
                $userData['username'] = $user->username;
                $userData['email'] = $user->email;
                $userData['phone'] = $user->phone;
                $userData['gender'] = $user->gender;
                $userData['dateOfBirth'] = $user->date_of_birth;
                $userData['country'] = $user->country;
                $userData['address'] = $user->address;
                $userData['height'] = $userHealthInfo->height;
                $userData['weight'] = $userHealthInfo->weight;
                $userData['health_conditions'] = $userHealthInfo->health_conditions;
                $userData['allergies'] = $userHealthInfo->allergies;
                $userData['current_medication'] = $userHealthInfo->current_medication;
                $userData['other_notes'] = $userHealthInfo->other_notes;
                
                $data = array("data" => $userData);
                return response()->json($data);
            }
            
        } else {
            $response_message =  array('success' => false, 'message' => "Unknown User");
            return response()->json($response_message);
        }
        
    }


    /**
     * Reset user details
     */
    public function resetUserDetails(Request $request)
    {
        $userId = $request->userId;
        $user = User::find($userId);
        if ($user) {
            if (User::where('email', $request->email)->where('user_id', '<>', $userId)->exists() || Admin::where('admin_email', $request->email)->exists() || Doctor::where('email', $request->email)->exists()) {
                $response_message =  array('success' => false, 'message' => 'Email already exists');
                return response()->json($response_message);
            } else {
                if (User::where('username', $request->username)->where('user_id', '<>', $userId)->exists() || Admin::where('admin_username', $request->username)->exists() || Doctor::where('username', $request->username)->exists()) {
                    $response_message =  array('success' => false, 'message' => 'Username already exists');
                    return response()->json($response_message);
                } else {
                    $user = User::find($userId);
                    $user->first_name = $request->firstName;
                    $user->last_name = $request->lastName;
                    $user->username = $request->username;
                    $user->email = $request->email;
                    $user->phone = $request->phone;
                    $user->address = $request->address;
                    $user->gender = $request->gender;
                    $user->country = $request->country;
                    $user->date_of_birth = $request->dateOfBirth;
                    $user->save();

                    $userHealthInfo = UserHealthInfo::where('uid', $userId)->first();
                    $userHealthInfo->height = $request->weight;
                    $userHealthInfo->weight = $request->height;
                    $userHealthInfo->health_conditions = $request->healthConditions;
                    $userHealthInfo->allergies = $request->allergies;
                    $userHealthInfo->current_medication = $request->currentMedication;
                    $userHealthInfo->other_notes = $request->otherNotes;
                    $userHealthInfo->save();
            
                    $response_message =  array('success' => true, 'message' => 'User info updated');
                    return response()->json($response_message);
                }
            }
        } else {
            $response_message =  array('success' => false, 'message' => "Unknown User");
            return response()->json($response_message);
        }
    }


    /**
     * Delete user account (for testing only)
     */
    public function deleteUserAccount($userId)
    {
        DB::statement("SET FOREIGN_KEY_CHECKS=0");
        DB::statement("DELETE from users WHERE user_id = $userId");
        DB::statement("SET FOREIGN_KEY_CHECKS=1;");

        $response_message =  array('success' => true, 'message' => "User deleted successfully");
        return response()->json($response_message);
    }


    /**
     * Gets all health resources
     */
    public function getAllHealthResources()
    {
        $data = HealthResource::all();
        $response_data =  array('data' => $data);
        return response()->json($response_data);
    }


    /**
     * Gets details of a health resource
     */
    public function getHealthResourceDetails($resourceId)
    {
        $data = HealthResource::find($resourceId);
        $response_data =  array('data' => $data);
        return response()->json($response_data);
    }


    /**
     * Gets all doctors
     */
    public function getAllDoctors()
    {
        $data = Doctor::all();
        $response_message =  array('data' => $data);
        return response()->json($response_message);
    }

    /**
     * Gets all pharmacies
     */
    public function getAllPharmacies()
    {
        $data = Pharmacy::all();
        $response_message =  array('data' => $data);
        return response()->json($response_message);
    }

    /**
     * Gets details of a pharmacy
     */
    public function getPharmacyDetails($resourceId)
    {
        $data = Pharmacy::find($resourceId);
        $response_data =  array('data' => $data);
        return response()->json($response_data);
    }


    /**
     * Get the details of a doctor
     */
    public function getDoctorDetials($doctorId)
    {
        $data = Doctor::find($doctorId);
        $response_message =  array('data' => $data);
        return response()->json($response_message);
    }


    /**
     * Gets article categories
     */
    public function getArticleCategories()
    {
        $articleCategories = ArticleCategory::select('category_id', 'category_name')->get();
        $response_data =  array('data' => $articleCategories);
        return response()->json($response_data);
    }


    /**
     * Get articles by category
     */
    public function getArticlesByCategory($articleCategoryName)
    {
        $data = array();
        $articleCategoryId = ArticleCategory::where('category_name', $articleCategoryName)->first()['category_id'];
        $categoryArticles = Article::where('article_cat_id', $articleCategoryId)->get();

        foreach ($categoryArticles as $key => $value) {
            $tempArray['id'] = $value['article_id'];
            $tempArray['title'] = $value['article_title'];
            $tempArray['thumbnail'] = $value['article_thumbnail'];
            $tempArray['content'] = $value['article_content'];
            $tempArray['upvotes'] = $value['article_upvotes'];
            $tempArray['downvotes'] = $value['article_downvotes'];
            $tempArray['views'] = $value['article_views'];
            $tempArray['category'] = $articleCategoryName;

            array_push($data, $tempArray);
        }

        $response_data =  array('data' => $data);
        return response()->json($response_data);
    }


    /**
     * Fetches article by id
     */
    public function fetchArticleById($articleId)
    {
        $article = Article::find($articleId);
        $data = array();

        if ($article) {
            $data['id'] = $article->article_id;
            $data['title'] = $article->article_title;
            $data['thumbnail'] = $article->article_thumbnail;
            $data['content'] = $article->article_content;
            $data['upvotes'] = $article->article_upvotes;
            $data['downvotes'] = $article->article_downvotes;
            $data['views'] = $article->article_views;
            $data['category'] = ArticleCategory::find($article->article_cat_id)->category_name;
        }

        $response_data =  array('data' => $data);
        return response()->json($response_data);
    }

    /**
     * Upvotes an article
     */
    public function upvoteArticle($articleId)
    {
        $article = Article::find($articleId);
        $numberOfUpvotes = $article->article_upvotes;
        $updatedUpvotes = $numberOfUpvotes + 1;
        $article->article_upvotes = $updatedUpvotes;

        if ($article->save()) {
            $response_message =  array('success' => true, 'message' => 'article upvote successful', 'numberOfUpvotes' => $updatedUpvotes );
            return response()->json($response_message);
        } else {
            $response_message =  array('success' => false, 'message' => 'article upvote unsuccessful', 'numberOfUpvotes' => $numberOfUpvotes );
            return response()->json($response_message);
        }
    }

    /**
     * Downvotes an article
     */
    public function downvoteArticle($articleId)
    {
        $article = Article::find($articleId);
        $numberOfDownvotes = $article->article_downvotes;
        $updatedDownVotes = $numberOfDownvotes + 1;
        $article->article_downvotes = $updatedDownVotes;

        if ($article->save()) {
            $response_message =  array('success' => true, 'message' => 'article downvote successful', 'numberOfDownvotes' => $updatedDownVotes );
            return response()->json($response_message);
        } else {
            $response_message =  array('success' => false, 'message' => 'article downvote unsuccessful', 'numberOfDownvotes' => $numberOfDownvotes );
            return response()->json($response_message);
        }
    }


    /**
     * Increases the views of an article
     */
    public function increaseArticleViews($articleId)
    {
        $article = Article::find($articleId);
        $articleViews = $article->article_views;
        $updatedViews = $articleViews + 1;
        $article->article_views = $updatedViews;

        if ($article->save()) {
            $response_message =  array('success' => true, 'message' => 'article view increase successful', 'numberOfViews' => $updatedViews );
            return response()->json($response_message);
        } else {
            $response_message =  array('success' => false, 'message' => 'article view increase unsuccessful', 'numberOfViews' => $articleViews );
            return response()->json($response_message);
        }
    }


    /**
     * Gets questions categories
     */
    public function getQuestionCategories()
    {
        $questionCategories = QuestionCategory::select('category_id', 'category_name')->get();
        $response_data =  array('data' => $questionCategories);
        return response()->json($response_data);
    }


    /**
     * Uploads question file
     */
    public function uploadQuestionFile(Request $request)
    {
        if ($request->hasFile('questionAttachedFile')) {
            $public_id = "bisa_question_media_".time();
            Cloudder::upload($request->file('questionAttachedFile')->getRealPath(),$public_id , array('folder'=> 'question_media'));
            $upload_result = Cloudder::getResult();

            if ($upload_result) {
                $response_message =  array('success' => true, 'message' => 'upload successful', 'url'=> $upload_result['secure_url']);
                return response()->json($response_message);
            } else {
                $response_message =  array('success' => false, 'message' => 'upload failed');
                return response()->json($response_message);
            }
        }
    }


    /**
     * Submits user question
     */
    public function submitUserQuestion(Request $request)
    {
        $question = new Question();
        $question->patient_id = $request->userId;
        $question->question_cat_id = $request->questionCategoryId;
        $question->question_content = $request->questionContent;
        $question->question_media_url = "n/a";
        $question->question_code = substr(md5(intval(time()) + rand()), 0, 10);

        if ($request->hasFile('questionAttachedFile')) {
            $public_id = "bisa_question_media_".time();
            Cloudder::upload($request->file('questionAttachedFile')->getRealPath(),$public_id , array('folder'=> 'question_media'));
            $upload_result = Cloudder::getResult();

            if ($upload_result) {
                $question->question_media_url = $upload_result['secure_url'];
            } else {
                $question->question_media_url = "n/a";
            }
        } else {
            $question->question_media_url = "n/a";
        }

        if ($question->save()) {
            $response_message =  array('success' => true, 'message' => 'question submitted successfully' );
            return response()->json($response_message);
        } else {
            $response_message =  array('success' => false, 'message' => 'Could not submit question. Please check your internet connection and try again' );
            return response()->json($response_message);
        }
    }


    /**
     * Replies user question
     */
    public function replyUserQuestion(Request $request)
    {
        $questionResponse = new QuestionResponse();
        $questionResponse->ques_id = $request->questionId;
        $questionResponse->responder_id = $request->userId;
        $questionResponse->responder_type = 'user';
        $questionResponse->question_response_content = $request->questionContent;

        if ($request->hasFile('questionAttachedFile')) {
            $public_id = "bisa_question_media_".time();
            Cloudder::upload($request->file('questionAttachedFile')->getRealPath(),$public_id , array('folder'=> 'question_media'));
            $upload_result = Cloudder::getResult();

            if ($upload_result) {
                $questionResponse->question_response_media_url = $upload_result['secure_url'];
            } else {
                $questionResponse->question_response_media_url = "n/a";
            }
        } else {
            $questionResponse->question_response_media_url = "n/a";
        }

        if ($questionResponse->save()) {
            $response_message =  array('success' => true, 'message' => 'reply submitted successfully' );
            return response()->json($response_message);
        } else {
            $response_message =  array('success' => false, 'message' => 'Could not submit reply. Please check your internet connection and try again' );
            return response()->json($response_message);
        }
    }


    /**
     * Gets user questions
     */
    public function getUserQuestions($userId)
    {
        $data = array();
        $userQuestions = Question::where('patient_id', $userId)->orderBy('question_id', 'DESC')->get();
        foreach ($userQuestions as $key => $value) {
            $temp_array['question_id'] = $value['question_id'];
            $temp_array['patient_id'] = $value['patient_id'];
            $temp_array['question_code'] = $value['question_code'];
            $temp_array['question_cat_id'] = $value['question_cat_id'];
            $temp_array['question_closed'] = $value['question_closed'];
            $temp_array['question_answered'] = $value['question_answered'];
            $temp_array['question_content'] = $value['question_content'];
            $temp_array['question_code'] = $value['question_code'];
            $temp_array['question_media_url'] = $value['question_media_url'];
            $temp_array['created_at'] = $value['created_at'];
            $questionCategoryDetails = QuestionCategory::find($value['question_cat_id']);
            $temp_array['question_category'] = $questionCategoryDetails->category_name;

            if ($value['question_answered'] == 'no') {
                $temp_array['question_threads'] = 1;
                $temp_array['response_doctor'] = "n/a";
            } else {
                $getQuestionAnswerStatus = QuestionResponse::where('ques_id', $value['question_id'])->get();
                $temp_array['question_threads'] = count($getQuestionAnswerStatus) + 1;

                $getDoctorAnsweringStatus = QuestionResponse::where('ques_id', $value['question_id'])->where('responder_type', 'doctor')->first();
                if ($getDoctorAnsweringStatus) {
                    $doctorDetails = Doctor::find($getDoctorAnsweringStatus['responder_id']);
                    $temp_array['response_doctor'] = "Dr. ".$doctorDetails->first_name." ".$doctorDetails->last_name;
                } else {
                    $temp_array['response_doctor'] = "n/a";
                }
            }

            array_push($data, $temp_array);
        }

        $response_message =  array('data' => $data );
        return response()->json($response_message);
    }


    /**
     * Gets user question details
     */
    public function getUserQuestionDetails($userId, $questionId)
    {
        $data = array();
        $questionsArray = array();

        $userQuestion = Question::where('patient_id', $userId)->where('question_id', $questionId)->first();

        if ($userQuestion) {
            $data['question_code'] = $userQuestion['question_code'];
            $questionCategoryDetails = $userQuestion['question_cat_id'];
            $data['question_category'] = QuestionCategory::find($userQuestion['question_cat_id'])->category_name;
            $data['question_id'] =  $userQuestion['question_id'];
            $data['question_closed'] =  $userQuestion['question_closed'];
            $data['question_closed'] =  $userQuestion['question_closed'];
            $data['patient_id'] = $userQuestion['patient_id'];

            $question_temp_array['question_content'] = $userQuestion['question_content'];
            $question_temp_array['question_media_url'] = $userQuestion['question_media_url'];
            $question_temp_array['created_at'] = $userQuestion['created_at'];
            $question_temp_array['creator'] = User::find($userId)->username;
            $question_temp_array['creator_type'] = 'user';

            array_push($questionsArray, $question_temp_array);

            $questionResponses = QuestionResponse::where('ques_id', $userQuestion['question_id'])->get();
            if (count($questionResponses) > 0) {

                foreach ($questionResponses as $k => $v) {
                    $question_temp_array['question_content'] = $v['question_response_content'];
                    $question_temp_array['question_media_url'] = $v['question_response_media_url'];
                    $question_temp_array['created_at'] = $v['created_at'];

                    if ($v['responder_type'] == 'doctor') {
                        $doctorDetails = Doctor::find($v['responder_id']);
                        $doctorName = $doctorDetails->first_name." ".$doctorDetails->last_name;
                        $question_temp_array['creator'] = $doctorName;
                        $question_temp_array['creator_type'] = 'doctor';
                    } else {
                        $question_temp_array['creator'] = User::find($userId)->username;
                        $question_temp_array['creator_type'] = 'user';
                    }

                    array_push($questionsArray, $question_temp_array);
                }
            }

            $data['question_threads'] =  $questionsArray;
        }

        $response_message =  array('data' => $data );
        return response()->json($response_message);
    }


    /**
     * Gets all videos
     */
    public function getVideos()
    {
       $data =  Video::all();
       $response_message =  array('data' => $data );
       return response()->json($response_message);
    }


    /**
     * Upvote video
     */
    public function upvoteVideo($videoId)
    {
        $video = Video::find($videoId);
        $numberOfUpvotes = $video->upvotes;
        $updatedUpvotes = $numberOfUpvotes + 1;
        $video->upvotes = $updatedUpvotes;

        if ($video->save()) {
            $response_message =  array('success' => true, 'message' => 'video upvote successful', 'numberOfUpvotes' => $updatedUpvotes );
            return response()->json($response_message);
        } else {
            $response_message =  array('success' => false, 'message' => 'video upvote unsuccessful', 'numberOfUpvotes' => $numberOfUpvotes );
            return response()->json($response_message);
        }
    }


    /**
     * Downvotes a video
     */
    public function downvoteVideo($videoId)
    {
        $video = Video::find($videoId);
        $numberOfDownvotes = $video->downvotes;
        $updatedDownvotes = $numberOfDownvotes + 1;
        $video->downvotes = $updatedDownvotes;

        if ($video->save()) {
            $response_message =  array('success' => true, 'message' => 'video downvote successful', 'numberOfDownvotes' => $updatedDownvotes );
            return response()->json($response_message);
        } else {
            $response_message =  array('success' => false, 'message' => 'video downvote unsuccessful', 'numberOfDownvotes' => $numberOfDownvotes );
            return response()->json($response_message);
        }
    }

    /**
     * Increases number of views of a video
     */
    public function increaseVideoViews($videoId)
    {
        $video = Video::find($videoId);
        $numberOfViews = $video->views;
        $updatedViews = $numberOfViews + 1;
        $video->views = $updatedViews;

        if ($video->save()) {
            $response_message =  array('success' => true, 'message' => 'video view number increase successful', 'numberOfViews' => $updatedViews );
            return response()->json($response_message);
        } else {
            $response_message =  array('success' => false, 'message' => 'video view number increase unsuccessful', 'numberOfViews' => $numberOfViews );
            return response()->json($response_message);
        }
    }
}
