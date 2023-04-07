TODO deadline 1:
- password reset
- update profile
- logout
- user should only be able to login when email is confirmed

DONE:
- register
- email verification
- Login
- list with to approve prompts for mods
- infinite scroll prompts

RULES:
- classes met hoofdletter vb. class User
- comments plaatsen waar nodig - iedereen moet je code kunnen begrijpen door deze te lezen
- engels voor variabele namen, interface in het engels
- commits in het engels
- commit often
- gebruik conventional commits(feat: chore: refactor: etc.)
- gebruik OOP! klasses die nodig zijn: User, Moderator, Prompt, Comment, Like, Report
- deel je databank aanpassingen met de rest of export deze en stuur het door!
- maak een nieuwe branch aan wanneer je denkt dat dit nodig is, wanneer je deze wil mergen bespreek je dit met de rest. 
- format je code - de code moet georganiseerd en leesbaar zijn

CONVENTIONAL COMMITS:
- nieuwe feature: feat
- bug fix: fix
- documentatie(comments): docs
- een variabele/functie naam aanpassen: refactor
- other changes: chore:
- teruggaan naar een vorige commit: revert

IMPORTANT GIT COMMANDS:
- git status
- git add ..
- git commit -m ".."
- git push -u origin ..
- git pull --rebase(alle changes op deze branch die op github staan maar niet in jou file komen erbij)
- git reset --hard(alle changes sinds laatste commit gaan weg)