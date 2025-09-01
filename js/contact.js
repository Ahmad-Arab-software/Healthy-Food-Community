document.addEventListener("DOMContentLoaded", async () => {
  // Fetch the user's IP address
  try {
    const ipResponse = await fetch("https://api.ipify.org?format=json");
    const ipData = await ipResponse.json();
    document.getElementById("ip_address").value = ipData.ip;
  } catch (error) {
    console.error("Error fetching IP address:", error);
  }
});

document
  .getElementById("contact-form")
  .addEventListener("submit", async (e) => {
    e.preventDefault();
    // Get form data
    const formData = new FormData(e.target);
    formData.append("access_key", "a756c955-d052-41aa-99f5-5dc8e75498c9");

    // Result message element
    const resultElement = document.getElementById("form-result");
    try {
      const response = await fetch("https://api.web3forms.com/submit", {
        method: "POST",
        body: formData,
      });

      const resultData = await response.json();
      const resultMessage = resultData.success
        ? "Message sent successfully!"
        : resultData.message || "Something went wrong. Please try again.";

      resultElement.textContent = resultMessage;
      resultElement.classList.remove("text-red-600");
      resultElement.classList.add("text-green-600");

      if (resultData.success) {
        document.getElementById("contact-form").reset();
      }
    } catch (error) {
      console.error("Error:", error);
      resultElement.textContent =
        "Error sending the message. Please try again later.";
      resultElement.classList.remove("text-green-600");
      resultElement.classList.add("text-red-600");
    }
  });
