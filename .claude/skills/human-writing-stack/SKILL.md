---
name: human-writing-stack
description: Orchestrates the local writing-patterns-audit, fabric-writing-patterns and anti-slop-writing skills. Use when the user asks to humanize, de-slop, tighten or rewrite text without a synthetic voice.
license: MIT
metadata:
  components:
    - writing-patterns-audit
    - fabric-writing-patterns
    - anti-slop-writing
---

# Human writing stack

This skill is the entry point for the three writing skills in this directory. Before editing any prose, read all three:

1. `.claude/skills/writing-patterns-audit/SKILL.md`
2. `.claude/skills/fabric-writing-patterns/SKILL.md`
3. `.claude/skills/anti-slop-writing/SKILL.md`

Apply them in this order:

1. **Audit:** identify mechanical structures, filler, unsupported claims and the text's actual job.
2. **Rewrite:** choose the appropriate mode: humanize, concise, or sustained argument.
3. **Anti-slop pass:** remove remaining clichés, symmetry, generic transitions and needless structure.
4. **Preserve:** keep facts, language, audience, format, product voice and uncertainty.

## Defaults

- If the user asks for a short answer, use concise mode and stop when the job is done.
- If the user asks for a rewrite, return the rewrite before any explanation.
- If the user asks for an audit, do not silently rewrite.
- If the text is UI copy, favor short labels and concrete actions over literary style.
- If the text is Czech, write natural Czech; do not translate English anti-patterns word-for-word.
- Never add a forced recap, “key takeaways” section or symmetrical bullet list.
- Never invent a personal voice, source, anecdote, statistic or certainty.

## Completion check

Before returning, verify that the result:

- has a clear job and a concrete first sentence
- contains no unused headings, filler transitions or redundant recap
- uses lists only where they help
- varies structure naturally without becoming chaotic
- preserves factual scope and the requested tone
