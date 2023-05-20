<?php

    namespace App\Http\Controllers;

    use Illuminate\Database\QueryException;
    use App\Models\User;
    use Illuminate\Support\Str;

    class Login extends Controller
    {
        protected Responder $responder;

        public function __construct()
        {
            $this->responder = new Responder();
        }

        public function RegisterUser(): array
        {
            if ($this->CredsMissing() === true) return $this->responder->message([401, 'Error: missing user credentials', null]);

            try {
                $token = Str::random(60);

                $user = new User();
                $user->name = request('user');
                $user->email = request('email');
                $user->password = request('password');
                $user->token = $token;
                $user->save();
            } catch (QueryException $e) {
                if ($e->getCode() == 23000)
                    return $this->responder->message([409, 'Error: user credentials already exist', null]);
            }
            return $this->responder->message(
                [200, 'Success: user credentials has been added to database',
                    ['user' => request('user'), 'email' => request('email')]]
            );
        }

        public function Verify()
        {
            if (auth()->attempt(['email' => request('email'), 'password' => request('password')])) {

                $user = User::where('email', request('email'))->first();
                return $this->responder
                    ->message([200, 'success: user authenticated', ['auth' => 1, 'user' => $user->name, 'token' => $user->token]]);
            }  else return $this->responder
                ->message([401, 'error: user unauthenticated',['auth' => 0]]);
        }

        static function AuthToken()
        {
            $user = User::where('token', request('token'))->first();
            if ( isset($user->id) ) return true;
            else return false;
        }

        private function CredsMissing(): bool
        {
            if (request('user') === null || request('email') === null || request('password') === null)
                return true;
            else return false;
        }
    }
