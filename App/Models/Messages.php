<?php

    namespace App\Models;
    class Messages
    {
        public const DOB_IS_INVALID = 'Invalid date format (YYYY-MM-DD)';
        public const DOB_IS_REQUIRED = 'Date of birth is required';
        public const DOB_IS_TOO_YOUNG = 'You are too young to register';
        public const EMAIL_IS_INVALID = 'Email is invalid';
        public const EMAIL_IS_REQUIRED = 'Email is required';
        public const EMAIL_IS_TAKEN = 'Email is taken';
        public const EMAIL_IS_TOO_LONG = 'Email is too long';
        public const NAME_IS_REQUIRED = 'Name is required';
        public const NAME_IS_TOO_LONG = 'Name is too long';
        public const PASSWORD_IS_INVALID = 'Password should be at least 8 chars long';
        public const PASSWORD_IS_MISS_MATCH = 'Passwords are not the same';
        public const PASSWORD_IS_REQUIRED = 'Password is required';
        public const INVALID_CREDENTIALS = 'Invalid Credentials';
        public const USER_UNAVAILABLE = 'No such user';
        public const NOT_AUTHENTICATED = 'Not authenticated';
        public const GENERIC_ERROR = 'Service temporarily unavailable';
    }