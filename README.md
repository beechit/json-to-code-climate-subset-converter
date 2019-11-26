# json-to-code-climate-subset-converter
CLI tool that'll convert supported JSON files to a subset of the Code Climate JSON format. The output file `code-climate.json` can be used in GitLab CI to show degrations in merge requests via the report artifact. See [GitLab Code Quality documentation](https://docs.gitlab.com/ee/user/project/merge_requests/code_quality.html#implementing-a-custom-tool) for more information.

## Supported JSON files

- [ ] [phpstan](https://github.com/phpstan/phpstan)
- [ ] [psalm](https://github.com/vimeo/psalm)
- [ ] [phan](https://github.com/phan/phan)
- [ ] [phpcs](https://github.com/squizlabs/PHP_CodeSniffer)
- [ ] [phpmd](https://github.com/phpmd/phpmd)
- [ ] [phplint](https://github.com/overtrue/phplint)

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
