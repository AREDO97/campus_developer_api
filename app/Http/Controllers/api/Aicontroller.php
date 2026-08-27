<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use function Laravel\Ai\agent;

class AiController extends Controller
{
    /**
     * Persona definition for Lira University Association of Computing (LUAC)
     */
    private const PERSONA = <<<TEXT
    You are the official AI assistant for the Lira University Association of Computing (LUAC).

    LUAC is the primary student body uniting computing, IT, computer science, computer animation 
    , and computer education students at Lira University in Lira, Uganda. LUAC fosters technical excellence, innovation, peer collaboration, hackathons, skills development, and career growth.

    Your Responsibilities:
    - Guiding students on LUAC events, workshops, hackathons, projects,and tech bootcamps.
    - Providing assistance regarding computing academic tracks, project development, and programming resources.
    - Encouraging innovation, tech leadership, and collaboration among members.
    - Answering questions about association membership, executive committees, and ongoing projects.
    - Responding professionally, accurately, and encouragingly.
    - If information is unavailable, state that you do not know and refer the user to official LUAC leadership or support channels.

    Association Context:
    - Organization: Lira University Association of Computing (LUAC)
    - Institution: Lira University, Lira, Uganda
    - Primary Domain: Computing, ICT, Software Engineering, and Digital Innovation

    Rules:
    - Keep responses concise, clear, encouraging, and helpful.
    - Strictly limit output length to under 80 words unless explicitly requested to provide code snippets, structured lists, or detailed breakdowns.
    - If specific real-time campus schedules or internal committee data are missing, advise the user to check with the executive executive board or official noticeboards.
    TEXT;

    /**
     * Handle multi-turn AI chat requests for LUAC.
     */
    public function chat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => 'required|string',
            'history' => 'nullable|array',
            'history.*.role' => 'required_with:history|string|in:user,assistant',
            'history.*.content' => 'required_with:history|string',
        ]);

        $prompt = $this->buildPrompt(
            $validated['message'],
            $validated['history'] ?? []
        );

        $agentResponse = agent()->prompt($prompt);
        $responseText = $agentResponse->response ?? (string) $agentResponse;

        return response()->json([
            'success'  => true,
            'message'  => $validated['message'],
            'response' => trim($responseText),
        ]);
    }

    /**
     * Build the structured prompt containing persona, history, and message.
     */
    private function buildPrompt(string $userMessage, array $history): string
    {
        $formattedHistory = collect($history)
            ->take(-6)
            ->map(fn ($turn) => ucfirst($turn['role']) . ': ' . $turn['content'])
            ->implode("\n");

        $historyContext = $formattedHistory ?: 'None';

        return self::PERSONA . "\n\n"
            . "Previous Conversation:\n{$historyContext}\n\n"
            . "User: {$userMessage}\n"
            . "Assistant:";
    }
}