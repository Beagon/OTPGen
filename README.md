# OTPGen
This project was made because I found it a hassle to keep grabbing my phone to get my OTP tokens. This project is still in development and will improve over time.

## How to use it
* Clone the project
* Do composer instal
* Make the otpgen file executable `chmod +x otpgen`
* Now you're ready to use it!
* Use `./otpgen add` to create your library and add your first token!


## Commands

* Add an OTP token and initializes your library: `./otpgen add`
* Delete a library entry: `./otpgen delete (NAME) or (ID)`
* Get an OTP code: `./otpgen code (NAME) or (ID)`
* List your OTP code library: `./otpgen library`

## Tips and Tricks

### Set your own working directory:
By default the working directory is the root directory of the application, you can define your own working directory by setting an enviroment variable called `OTPGEN_DIR`.

### Install OTPGen globably:
Make a softlink to your /usr/bin directory `ln -s DIRECTORY/otpgen /usr/bin/otpgen` (replace directory with the root directory of otpgen)
* WARNING 1: This will need all users to set a enviroment variable to a path they can fully access!
* WARNING 2: This will make all users be able to list and use the OTP codes in the applications root directory!