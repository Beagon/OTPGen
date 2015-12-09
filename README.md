===OTPGen

This project was made because I found it a hassle to keep grabbing my phone to get my OTP tokens. This project is still in development and will improve over time.

==How to use it
*Clone the project
*Do composer install
*Create a .otp file and fill it with your tokens in YAML format e.g.
"github": "Your token here"
*Make the otpgen file executable (chmod +x otpgen)
*Now you're ready to use it!
*Use ./otpgen code github and you will get a token back!
