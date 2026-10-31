---
name: fabric-writing-patterns
description: Applies concise, human-sounding writing patterns inspired by Fabric's humanize, write_concise and write_essay patterns. Use for summaries, web copy, documentation and editorial rewrites.
license: MIT
metadata:
  inspired_by: DanielMiessler/Fabric
  source: https://github.com/danielmiessler/Fabric
  patterns: humanize, write_concise, write_essay
---

# Fabric writing patterns

Apply the smallest pattern that fits the job. Do not turn every request into an essay.

## Humanize

- Write like a capable person explaining something to another person.
- Use plain, specific language and contractions when the language permits them.
- Keep useful irregularity: short sentences can sit beside longer ones.
- Remove corporate filler, inflated claims and vague emotional language.
- Do not pretend to have personal experience, feelings or certainty you do not have.
- Match the source's language, register and point of view.

## Write concise

- Identify the single job of the text before editing.
- Keep facts, constraints, decisions and next actions.
- Delete repetition, throat-clearing, meta-commentary and decorative transitions.
- Prefer one strong sentence to two weaker explanations.
- Use bullets only for genuinely separate items.
- Stop when the job is complete; do not append a recap by habit.

## Write essay

Use only when the user asks for an essay, article or sustained argument:

1. State the controlling idea early.
2. Develop one idea per paragraph.
3. Use evidence or examples that directly serve the idea.
4. Let the structure follow the argument, not a fixed number of sections.
5. End with a consequence, implication or clear final point, not a summary of headings.

## Guardrails

- Never invent sources, quotations, data or lived experience.
- Preserve factual uncertainty and distinguish evidence from interpretation.
- Do not imitate a living author's distinctive voice unless the user supplied a legitimate style guide or sample and the request is allowed.
- For Czech text, preserve natural Czech word order and diacritics; do not translate English prompt habits literally.
