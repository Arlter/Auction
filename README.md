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
    - or is this actually ideal?