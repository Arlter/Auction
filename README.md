# COMP0178-Auction

221110 notes
- after successful registration, directs back to browse.php for now, but it is possible to redirect to another page (e.g. a separate login page) instead
- used sessions to save inputs temporarily, and only refreshes input rows relevant to the error on screen (password and confirmation are never saved)
- have to create session that save user username after successful account registration
- exception handling - how?