# Contribute

Thank you for being interesting in this package and wanting to contribute to it.

## Coding

New code should be introduced to the codebase through issues that specify what you are trying to accomplish.
I understand that sometimes you need to add/update/delete code otherwhere in the codebase to achieve this goal.
This is why my test always merges master in to ensure that your code stays functional/executable during development.

Please adhere these pointers:
* Support selected PHP Versions | Ref: [Master/Composer.json](https://github.com/AlexWestergaard/php-ga4/blob/master/composer.json)
* Pass current tests without modification; unless clearly explaining why the change is necessary/required | `> vendor/bin/phpunit`
* PHPUnit tests should confidently ensure that code doesn't fail/error in unwated ways (eg. E_WARNINGS or missing paranthesis)
* At least try to follow PSR<1, 4, 12> and \*PSR<5, 10> for documentation | Ref: [PHP FIG.](https://www.php-fig.org/psr/)
* Commits should explain what is achived and not what changed / eg. ask yourself: "This commit will...(?)"
* Tabs/Tabulations should appear as 4 spaces

## Roadmap

Future direction will primarily be directed by milestones of issues.
If no milestone is present, issues marked with `Bug` and `Enhancement` are considered goals.
