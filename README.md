# COMP0178-Auction

221110 notes
- after successful registration, directs back to browse.php for now, but possible separate login page and redirect there instead in the future
- currently, when the user enters invalid input, all previous inputs are gone and user has to enter them again
- have to create sessions that save user username after successful account registration

Sessions created:
- $_SESSION["alert"]
- $_SESSION["reg_success"]

Not tested: (does this need a query first?)
- $_SESSION["accountID"] = $row["accountID"];
- $_SESSION["accountUsername"] = $row["accountUsername"];
- $_SESSION["account_role"] = $row["accountType"];
