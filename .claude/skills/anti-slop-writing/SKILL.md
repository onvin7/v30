---
name: anti-slop-writing
description: Removes detectable AI-writing habits from copy while preserving meaning, accuracy and the appropriate genre. Use for UI copy, documentation, reports, emails and Czech prose.
license: MIT
metadata:
  inspired_by: steven2358/awesome-generative-ai and f/awesome-chatgpt-prompts
  sources:
    - https://github.com/steven2358/awesome-generative-ai
    - https://github.com/f/awesome-chatgpt-prompts
---

# Anti-slop writing

This is an editing pass, not a request to disguise false authorship. Make the text clearer and less formulaic; never fabricate human experience or use it to misrepresent evidence.

## Remove

- “In today's fast-paced world”, “seamless”, “unlock”, “game-changing” and similar hype
- empty lead-ins, generic conclusions and “it is worth noting” transitions
- repetitive “not X, but Y” constructions
- overbalanced triples, parallel bullets and identical paragraph cadences
- vague references such as “this”, “it” or “the above” when a concrete noun is clearer
- adjectives that do not change the reader's understanding
- fake precision, unsupported superlatives and unverified social proof
- headings, bullets or callouts added only to create visual symmetry

## Improve

- Anchor claims in the supplied facts.
- Use concrete examples, names, dates and locations when they are already available.
- Mix sentence lengths without making the rhythm deliberately random.
- Give each paragraph one job.
- Keep interface labels short and remove punctuation from short labels.
- Keep the requested tone: formal copy stays formal, a warm note stays warm.

## Final checks

- Every claim can be traced to the input or an explicitly provided source.
- The first sentence says what the reader needs to know.
- No paragraph merely restates another paragraph.
- Lists are necessary, uneven where natural and not padded.
- The result still sounds like the product, organization or author.

When asked for a score, report only the detected problems and concrete edits. Do not claim that text can reliably evade an AI detector.
