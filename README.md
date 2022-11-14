<<<<<<< Updated upstream
=======
<<<<<<< Updated upstream
# COMP0178-Auction
=======
>>>>>>> Stashed changes
# COMP0178-Auction

221110 notes
- after successful registration, directs back to browse.php for now, but it is possible to redirect to another page (e.g. a separate login page) instead
- used sessions to save inputs temporarily, and only refreshes input rows relevant to the error on screen (password and confirmation are never saved)
- have to create session that save user username after successful account registration
- exception handling - how?

221111 notes
- now specific alerts show when an input row is empty or contains only space
- however, it cannot check all inputs simultaneously, so validation-related alert shows up one by one
    - may have to improve the structure?
<<<<<<< Updated upstream
    - or is this actually ideal?
=======
    - or is this actually ideal?


Regarding data validation
- current structure used in register is:
    - "required" in <input> checks for empty inputs on the client side
    - if all inputs are filled in, send to process_register.php
    - set sessions to save data temporarily
        - so if one input is invalid, only that one is erased
    - check for empty inputs on the server side

    - start validation
        - if invalid:
            - unset data session
            - set session for alert
            - redirect to register.php with error message in url, exit process_register.php script
            - echo alert message
            - unset alert session
            - shows red alert message under input box if data session is unset or empty (though client side validation normally is enough)
        (didn't think of using $_GET["error"] before, oops)
        (also can only validate one input at a time, so at most only one error shows up for every submission attempt)

    - username validation:
        - must be 4-20 characters long
        - contains no space
        - alphanumeric characters only
        - does not exist in database
    
    - password validation:
        - must be 8-20 characters long
        - contains no space
        - must contain at least 1 letter and 1 number
        - accepts alphanumeric characters and symbols from !@#$% only

    - password confirmation:
        - must match password
    
    - email validation:
        - sanitised using filter
        - validate format using filter

    - phone number validation:
        - omit " ", ".", "-", "(", ")"
        - must start with + sign followed by 7-15 numbers
>>>>>>> Stashed changes
>>>>>>> Stashed changes
