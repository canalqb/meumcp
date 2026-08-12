const input = document.getElementById("autocompleteInput");
const wrapper = document.getElementById("tokenWrapper");
const menu = document.getElementById("autocompleteMenu");

const items = [];
for (let i = 0; i < 50; i++) items.push({ label: "Option " + i, value: "opt" + i });

let tokens = [];

function renderTokens() {
  wrapper.querySelectorAll(".token").forEach(t => t.remove());
  tokens.forEach((t, i) => {
    const tokenEl = document.createElement("div");
    tokenEl.classList.add("token");
    tokenEl.textContent = t.label;
    const removeSpan = document.createElement("span");
    removeSpan.textContent = "×";
    removeSpan.addEventListener("click", () => {
      tokens.splice(i, 1);
      renderTokens();
      input.focus();
      showMenu();
    });
    tokenEl.appendChild(removeSpan);
    wrapper.insertBefore(tokenEl, input);
  });
}

function showMenu() {
  const val = input.value.toLowerCase();
  menu.innerHTML = "";
  const filtered = items.filter(item => {
    return !tokens.some(t => t.value === item.value) && item.label.toLowerCase().includes(val);
  });

  filtered.forEach(item => {
    const li = document.createElement("li");
    const a = document.createElement("a");
    a.textContent = item.label;
    a.href = "#";
    a.addEventListener("mousedown", e => e.preventDefault());
    a.addEventListener("click", e => {
      e.preventDefault();
      tokens.push(item);
      renderTokens();
      input.value = "";
      menu.style.display = "none";
    });
    li.appendChild(a);
    menu.appendChild(li);
  });

  menu.style.display = filtered.length ? "block" : "none";
}

input.addEventListener("input", showMenu);

// Abrir dropdown sempre que clicar no wrapper
wrapper.addEventListener("click", () => {
  input.focus();
  showMenu();
});

// Fechar ao clicar fora
document.addEventListener("click", e => {
  if (!wrapper.contains(e.target)) menu.style.display = "none";
});

// Navegação por teclado
input.addEventListener("keydown", e => {
  const active = menu.querySelector("a.active");
  if (e.key === "ArrowDown") {
    e.preventDefault();
    if (!active) menu.querySelector("a")?.classList.add("active");
    else {
      active.classList.remove("active");
      const next = active.parentElement.nextElementSibling;
      if (next) next.querySelector("a").classList.add("active");
      else menu.querySelector("a").classList.add("active");
    }
  } else if (e.key === "ArrowUp") {
    e.preventDefault();
    if (!active) return;
    active.classList.remove("active");
    const prev = active.parentElement.previousElementSibling;
    if (prev) prev.querySelector("a").classList.add("active");
    else menu.querySelector("a:last-child").classList.add("active");
  } else if (e.key === "Enter") {
    e.preventDefault();
    active?.click();
  } else if (e.key === "Backspace" && input.value === "") {
    tokens.pop();
    renderTokens();
    showMenu();
  }
});