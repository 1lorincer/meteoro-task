<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/register",
     *      operationId="register",
     *      tags={"Auth"},
     *      summary="Регистрация нового пользователя",
     *      description="Создает нового пользователя и возвращает токен авторизации",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name","email","password","password_confirmation"},
     *              @OA\Property(property="name", type="string", example="Иван Иванов"),
     *              @OA\Property(property="email", type="string", format="email", example="ivan@example.com"),
     *              @OA\Property(property="password", type="string", format="password", example="password123"),
     *              @OA\Property(property="password_confirmation", type="string", format="password", example="password123")
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Успешная регистрация",
     *          @OA\JsonContent(
     *              @OA\Property(property="user", type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="name", type="string", example="Иван Иванов"),
     *                  @OA\Property(property="email", type="string", example="ivan@example.com"),
     *                  @OA\Property(property="role", type="string", example="user")
     *              ),
     *              @OA\Property(property="token", type="string", example="1|abcdef123456...")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Ошибка валидации"
     *      )
     * )
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
        ]);
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * @OA\Post(
     *      path="/api/login",
     *      operationId="login",
     *      tags={"Auth"},
     *      summary="Вход в систему",
     *      description="Аутентификация пользователя и получение токена",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"email","password"},
     *              @OA\Property(property="email", type="string", format="email", example="ivan@example.com"),
     *              @OA\Property(property="password", type="string", format="password", example="password123")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Успешная аутентификация",
     *          @OA\JsonContent(
     *              @OA\Property(property="user", type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="name", type="string", example="Иван Иванов"),
     *                  @OA\Property(property="email", type="string", example="ivan@example.com"),
     *                  @OA\Property(property="role", type="string", example="user")
     *              ),
     *              @OA\Property(property="token", type="string", example="1|abcdef123456...")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Неверные учетные данные"
     *      )
     * )
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Неверный email или пароль'],
            ]);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    /**
     * @OA\Post(
     *      path="/api/logout",
     *      operationId="logout",
     *      tags={"Auth"},
     *      summary="Выход из системы",
     *      description="Аннулирование токена текущего пользователя",
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Успешный выход",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Вы вышли из системы")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Не авторизован"
     *      )
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Вы вышли из системы']);
    }

    /**
     * @OA\Get(
     *      path="/api/user",
     *      operationId="getUser",
     *      tags={"Auth"},
     *      summary="Получить данные текущего пользователя",
     *      description="Возвращает информацию о текущем авторизованном пользователе",
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Успешно",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="integer", example=1),
     *              @OA\Property(property="name", type="string", example="Иван Иванов"),
     *              @OA\Property(property="email", type="string", example="ivan@example.com"),
     *              @OA\Property(property="role", type="string", example="user")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Не авторизован"
     *      )
     * )
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

}
