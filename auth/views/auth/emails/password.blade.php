Click here to reset your password: <a href="{{ $link = url('password/reset', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}" data-test="reset-password-link" > {{ $link }} </a>
