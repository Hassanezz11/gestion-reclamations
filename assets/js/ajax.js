/**
 * Example of a simple AJAX helper using Fetch API
 */
async function sendAjax(url, data = {}) {
  const response = await fetch(url, {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: new URLSearchParams(data)
  });
  return response.text();
}
