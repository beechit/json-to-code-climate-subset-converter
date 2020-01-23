![GitHub Workflow Status](https://img.shields.io/github/workflow/status/beechit/json-to-code-climate-subset-converter/Run%20PHPUnit%20tests?label=PHPUnit) ![GitHub Workflow Status](https://img.shields.io/github/workflow/status/beechit/json-to-code-climate-subset-converter/Run%20PHPStan?label=PHPStan)

# json-to-code-climate-subset-converter
CLI tool that'll convert supported JSON files to a subset of the Code Climate JSON format. The output file `code-climate.json` can be used in GitLab CI to show degrations in merge requests via the report artifact. See [GitLab Code Quality documentation](https://docs.gitlab.com/ee/user/project/merge_requests/code_quality.html#implementing-a-custom-tool) for more information.

# Work in progress

Please note this project is actively worked on but it's not ready for production use.

- [ ] Write better exceptions for when file can't be found
- [ ] Write tests for console command
- [ ] Add static analysis

## Supported JSON files

- [x] [phpstan](https://github.com/phpstan/phpstan)
- [x] [psalm](https://github.com/vimeo/psalm)
- [x] [phan](https://github.com/phan/phan)
- [x] [phpcs](https://github.com/squizlabs/PHP_CodeSniffer)
- [ ] [phpmd](https://github.com/phpmd/phpmd)
- [x] [phplint](https://github.com/overtrue/phplint)

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

## Running the CLI command

By default, no driver is included in the run. You'll have to manually specify what drivers are to be included whilst converting.

For example, if you wish to run the converter with Psalm, you'll run:

```sh
php converter convert --psalm
```
The included converters are enabled via their respective flags:

- `php converter convert --phpstan`
- `php converter convert --psalm`
- `php converter convert --phan`
- `php converter convert --phpcs`
- `php converter convert --phplint`

If you wish to specify the Psalm input file (JSON format), you'll run:

```sh
php converter convert --psalm --psalm-json-file=path/to/file.json
```

- `php converter convert --phpstan --phpstan-json-file=path/to/file.json`
- `php converter convert --psalm --psalm-json-file=path/to/file.json`
- `php converter convert --phan --phan-json-file=path/to/file.json`
- `php converter convert --phpcs --phpcs-json-file=path/to/file.json`
- `php converter convert --phplint --phplint-json-file=path/to/file.json`

If you're working on a project that supports multiple tools, you're free to add as many supported drivers in your command.

```sh
php converter convert --phpstan --psalm --phpcs
```

## JSON output

By default a `code-climate.json` file is generated for you to use.
