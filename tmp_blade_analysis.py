from pathlib import Path
import re
p = Path('resources/views/perjanjian/create.blade.php')
text = p.read_text(encoding='utf-8')
lines = text.splitlines()
pattern = re.compile(r'@([a-zA-Z_]+)')
directives = []
for i, line in enumerate(lines, 1):
    for m in pattern.finditer(line):
        directives.append((i, m.group(1), line.strip()))
print('Total directives:', len(directives))
for tok in ['section', 'endsection', 'if', 'endif', 'foreach', 'endforeach', 'for', 'endfor', 'while', 'endwhile', 'switch', 'endswitch', 'isset', 'endisset', 'empty', 'endempty', 'php', 'endphp', 'include', 'extends', 'json', 'csrf', 'auth', 'guest', 'yield', 'push', 'endpush', 'stack']:
    count = sum(1 for _, t, _ in directives if t == tok)
    if count:
        print(tok, count)
print('--- directives around section markers ---')
for i, tok, line in directives:
    if tok in ('section','endsection','if','endif','foreach','endforeach','php','endphp'):
        print(i, tok, line)
