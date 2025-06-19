
document.addEventListener("DOMContentLoaded", () => {
  fetch("get_issues.php")
    .then(response => response.json())
    .then(data => {
      const tbody = document.getElementById("issueTableBody");
      data.forEach(issue => {
        const row = document.createElement("tr");
        row.innerHTML = `
          <td>${issue.id}</td>
          <td>${issue.category}</td>
          <td>${issue.location}</td>
          <td>${issue.status}</td>
        `;
        tbody.appendChild(row);
      });
    })
    .catch(error => console.error("Error fetching issues:", error));
});
