---
name: writing-patterns-audit
description: Audits and rewrites prose that sounds mechanically generated. Use when text has symmetrical lists, repeated sentence shapes, canned transitions, forced summaries, generic warmth or other Claude-like writing patterns.
license: MIT
metadata:
  inspired_by: richard-to/claude-writing-prompts
  source: https://github.com/richard-to/claude-writing-prompts
---

# Writing patterns audit

Use this skill to make writing sound intentional and specific without flattening its voice.

## Audit before rewriting

Check for:

- symmetrical bullets or paragraphs that all have the same length and syntax
- repeated openings such as “It is important to…”, “It is worth noting…”, “In conclusion…”
- forced three-part structures, stacked synonyms and mirrored contrasts
- generic praise, synthetic warmth, motivational filler or empty intensifiers
- a recap that only repeats the preceding text
- headings that exist only to make the answer look organized
- overuse of em dashes, semicolons, parenthetical asides or colon-led lists
- claims that are broader or more confident than the source supports

## Rewrite rules

1. Keep the facts, audience, language and requested format.
2. Prefer concrete nouns and verbs over abstract framing.
3. Vary sentence length and paragraph shape naturally.
4. Keep a list only when it improves scanning. Use an uneven number of points when the content calls for it.
5. Remove a heading or summary when it adds no information.
6. Replace canned transitions with a direct sentence or a paragraph break.
7. Do not add a personal anecdote, opinion or claim that is not in the source.
8. Preserve a deliberate rhetorical pattern when the author clearly chose it.

## Output

Return the revised text first. If an audit was requested, follow it with a short table of the most important detected patterns and their fixes. Do not provide a generic recap.
