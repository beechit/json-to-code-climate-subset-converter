# json-to-code-climate-subset-converter
CLI tool that'll convert supported JSON files to a subset of the Code Climate JSON format

## Supported JSON files

- [ ] [phpstan](https://github.com/phpstan/phpstan)
- [ ] [psalm](https://github.com/vimeo/psalm)
- [ ] [phan](https://github.com/phan/phan)
- [ ] [phpcs](https://github.com/squizlabs/PHP_CodeSniffer)
- [ ] [phpmd](https://github.com/phpmd/phpmd)
- [ ] [phplint](https://github.com/overtrue/phplint)

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
