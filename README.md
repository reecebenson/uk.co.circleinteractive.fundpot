# FundingPot (uk.co.circleinteractive.fundpot)

FundingPot is an extension for CiviCRM that allows you to create a Case that has a specified fund. These funds can be distributed to CiviEvents, and you can track these funds from the Case (FundPot) or the Event that the funds have been allocated to.

The extension is licensed under [AGPL-3.0](LICENSE.txt).

## Requirements

* PHP v5.4+
* CiviCRM (5.x)

## Installation (CLI, Git)

System Administrators and Developers may clone the [Git](https://en.wikipedia.org/wiki/Git) repo for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
git clone https://github.com/reecebenson/uk.co.circleinteractive.fundpot.git
cv en fundpot
```

## Usage

- Create a new Case with the type "FundPot" and specify a Funding Amount for this Case
- Create/edit an Event, and under Info & Settings, specify a Funding Amount for the Event Cost
- Create a new Contribution, set the Contribution Source to be the subject of the Case you've just created, and set the Event Reference to be the event you have specified a Funding Amount (Event Cost) for.
- When viewing the Case, you can now track the Outgoing Funds. Similarly, when viewing the Event, you can track the Incoming Funds.

## Known Issues

- Stable as of 1st June 2019