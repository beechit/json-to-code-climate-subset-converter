![GitHub Workflow Status](https://img.shields.io/github/workflow/status/beechit/json-to-code-climate-subset-converter/Run%20PHPUnit%20tests?label=PHPUnit) ![GitHub Workflow Status](https://img.shields.io/github/workflow/status/beechit/json-to-code-climate-subset-converter/Run%20Infection?label=Infection) ![GitHub Workflow Status](https://img.shields.io/github/workflow/status/beechit/json-to-code-climate-subset-converter/Run%20PHPStan?label=PHPStan) ![GitHub Workflow Status](https://img.shields.io/github/workflow/status/beechit/json-to-code-climate-subset-converter/Run%20Psalm?label=Psalm) ![GitHub Workflow Status](https://img.shields.io/github/workflow/status/beechit/json-to-code-climate-subset-converter/Run%20PHP_CodeSniffer?label=PHP_CodeSniffer) ![GitHub Workflow Status](https://img.shields.io/github/workflow/status/beechit/json-to-code-climate-subset-converter/Run%20Phan?label=Phan) ![GitHub Workflow Status](https://img.shields.io/github/workflow/status/beechit/json-to-code-climate-subset-converter/Run%20PHPLint?label=PHPLint)

# json-to-code-climate-subset-converter
CLI tool that'll convert supported JSON files to a subset of the Code Climate JSON format. The output file `code-climate.json` can be used in GitLab CI to show degrations in merge requests via the report artifact. See [GitLab Code Quality documentation](https://docs.gitlab.com/ee/user/project/merge_requests/code_quality.html#implementing-a-custom-tool) for more information.

## Supported JSON files

- [x] [phpstan](https://github.com/phpstan/phpstan)
- [x] [psalm](https://github.com/vimeo/psalm)
- [x] [phan](https://github.com/phan/phan)
- [x] [phpcs](https://github.com/squizlabs/PHP_CodeSniffer)
- [ ] [phpmd](https://github.com/phpmd/phpmd)
- [x] [phplint](https://github.com/overtrue/phplint)
- [x] [php-cs-fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer)

## Example input

```json
[
    {
        "type": "issue",
        "type_id": 11007,
        "check_name": "PhanUndeclaredClassConstant",
        "description": "UndefError PhanUndeclaredClassConstant Reference to constant class from undeclared class \\PhpParser\\Node\\Stmt\\ClassMethod",
        "severity": 10,
        "location": {
            "path": "app/Class.php",
            "lines": {
                "begin": 32,
                "end": 34
            }
        }
    }
]
```

## Example output

```json
[
    {
        "description": "(Phan) UndefError PhanUndeclaredClassConstant Reference to constant class from undeclared class \\PhpParser\\Node\\Stmt\\ClassMethod",
        "fingerprint": "fd46675f22771e90045b745429e46682",
        "location": {
            "path": "app/Class.php",
            "lines": {
                "begin": 32,
                "end": 34
            }
        }
    }
]
```

## Documentation

Please refer to the project's WIKI entries for documentation: [WIKI](https://github.com/beechit/json-to-code-climate-subset-converter/wiki)
