<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;

class OpenAIService
{
    protected $apiKey ;
    protected $apiUrl = "https://api.openai.com/v1/chat/completions";

    public function __construct()
    {
        $this->apiKey = env('OPEN_AI_API_KEY');
    }
    public function generateTravelPlan($userInput)
    {
        $messages =[
            [
                'role'=> 'user',
                'content'=> $this->createPrompt($userInput),

            ]

        ];
        $response= Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' =>'application/json',

        ])->post($this->apiUrl,[
            'model' =>'gpt-3.5-turbo',
            'messages'=>$messages,
            'temperature'=> 0.7,
        ]);

        return $response->json();
    }
    public function regenerateTravelPlan($userInput)
    {
        $messages =[
            [
                'role'=> 'user',
                'content'=> $this->createPromptForUpdate($userInput),

            ]

        ];
        $response= Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' =>'application/json',

        ])->post($this->apiUrl,[
            'model' =>'gpt-3.5-turbo',
            'messages'=>$messages,
            'temperature'=> 0.7,
        ]);

        return $response->json();
    }

    protected function createPrompt($userInput){
        $prompt = "Create detailed travel plan for user with the following details:\n";
        $prompt .= "Destination ".$userInput['destination']."\n";
        $prompt .= "Start date ".$userInput['start_date']."\n";
        $prompt .= "End date ".$userInput['end_date']."\n";
        $prompt .= "Return one new plan for  for that day in JSON format with the following structure";
        $prompt .= "{ \"Plans\": [{\"day\": \"number\", \"description\": \"Plan description\", \"activity\": \"activity\" }]";
        return $prompt;
        
    }
    protected function createPromptForUpdate($userInput){
        $prompt = "Regenerate this day ".$userInput['day'] ." plan:\n";
        // $prompt .= "Destination ".$userInput['destination']."\n";
        // $prompt .= "Start date ".$userInput['start_date']."\n";
        // $prompt .= "End date ".$userInput['end_date']."\n";
        $prompt .= "Please provide a single plan in JSON format with the following structure: ";
        $prompt .= "{ \"Plans\": {\"day\": \"number\", \"description\": \"Plan description\", \"activity\": \"activity\" }";
        return $prompt;
    }
}