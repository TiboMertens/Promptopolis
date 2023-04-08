//BRITT 
- Ik heb al een functie getUserDetails() aangemaakt die jij ook best gebruikt voor het profiel te updaten, zo hebben we geen duplicate functies.
- TIP: $_SESSION & ga spieken in promptDetails.php

//QUINTT
- Bel me even wanneer je start zodat ik het email versturen gedeelte kan uitleggen dan moet je je niet doodzoeken. 
- Ik heb forgotPassword.php al aangemaakt en gelinked voor jou - file is nog leeg

//BRITT & QUINTT
- Neem de code van login.php, signup.php, User.php, verification.php eens rustig door. Dit zijn mijn features van deadline-1. 
  Als je deze eens lees heb je misschien al een beter idee hoe je je eigen features maakt. 

- Moderator.php, Prompt.php, loadPromptsToApprove.php, promptDetails.php, showcase.php & ajax folder is al voor deadline-2. Ik heb dit al gemerged omdat
  het jullie deel van de code niet lastig valt maar het wel Britt kon helpen zoals de getUserDetails() functie die nu al klaar staat xD

- Als iets niet lukt, gebruik chatGPT/php.net/google/youtube of stel een vraag op stackoverflow. Of je stuurt me even een berichtje dan help ik

- De mappen js, json & vendor hebben te maken met de installatie van sendgrid(email provider) & tailwindcss. Niets van aantrekken

TODO deadline 1:
- password reset
- update profile
- logout
- user should only be able to login when email is confirmed
- verified users should be able to post prompts without approval needed

DONE:
- register
- email verification
- Login
- list with to approve prompts for mods
- infinite scroll prompts
- ability to approve prompts

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

VERANDEREN VAN BRANCH:
- CTRL+SHIFT+P --> Git: checkout to --> kies je branch. 

BRANCH AANMAKEN:
- doe best in github zelf, niet in vscode
- linksboven op main klikken --> view all branches --> rechtsboven new branch --> in vscode veranderen naar die branch.

BRANCH MERGEN:
- In github zelf, check met de rest

TIPS:
-  om makkelijk en snel te commiten gebruik Ctrl+Shift+G. Dan schrijf je je commit, klik je op '+' bij de files die je in de commit wilt steken
   en dan klik je commit. Om te pushen klik je de sync knop. Dan hoef je de terminal niet te gebruiken