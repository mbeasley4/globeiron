/**
 * Dynamic block — frontend output is handled entirely by render.php.
 * save() must return null so WordPress knows not to store or validate
 * static HTML for this block.
 */
export default function Save() {
  return null;
}
