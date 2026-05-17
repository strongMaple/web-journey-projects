// Translations
// translation.js
// projectsTitle
const translations = {
  de: {
    // Navigation
    about: "Über mich",
    projects: "Projekte",
    contact: "Kontakt",

    // Home Section
    jobTitle: "Webseitenentwickler",
    homeDesc: `<span style="font-family: 'Great Vibes', cursive">Maple_</span> konzentriert sich auf den Aufbau zugänglicher und benutzerfreundlicher Webseiten.`,

    // About Section
    aboutTitle: "ÜBER MICH",
    aboutP1: `Ich bin ein leidenschaftlicher Technik-Fan und <b class="tak">Informatikstudent</b> an der <b class="tak">University of Nigeria, Nsukka (UNN)</b>. Neben dem Studium baue und style ich moderne Benutzeroberflächen <b>(UI)</b> und arbeite regelmäßig mit React, Node.js, Express.js und MongoDB.`,

    aboutP2: `Ich nutze den <b>MERN</b>-Stack <b class="tak">(MongoDB</b>, <b class="tak">Express.js</b>, <b class="tak">React</b>, <b class="tak">Node.js)</b> sowie GitHub für Entwicklung und Versionierung. Als <b class="tak">UNN-Informatikstudent</b> lerne ich ständig dazu und helfe in meiner Freizeit Anfängern, HTML und CSS zu verstehen.`,

    aboutP3: `Ich arbeite daran, mein Wissen über Backend-Systeme, <b class="tak">Server-APIs</b> und Security-Konzepte zu vertiefen. <b class="tak">Mein</b> Workflow umfasst Tools wie <b>VS Code</b> und <b>PyCharm</b>, während ich meine JavaScript- und Python-Skills ausbaue – auch mit Blick auf Chancen in <b class="tak">Deutschlands</b> Tech-Szene.`,

    aboutP4: `Ob mehrsprachiger Code, <b class="tak">Security</b> oder echte Projekte – ich bringe viel <b class="tak">Leidenschaft</b> und Disziplin mit. Als <b class="tak">Informatikstudent an der UNN</b> arbeite ich daran, global mitzuhalten und eine Zukunft aufzubauen, in der ich erschaffe, beitrage und <b class="tak">international</b> wachsen kann.`,

    aboutP5: "Maple_",

    // Projects Section
    projectsTitle: "PROJEKTE",
    cssIntroTitle: "CSS-Intro <span>&#8599;</span>",
    cssDesc: `Ein deutsch inspiriertes Projekt, basierend auf einem YouTube-Tutorial von <a href="https://youtube.com/@traversymedia?si=rlugzAA0gD6-DKwj" rel="noopener noreferrer" target="_blank">Brad Traversy</a>. Es behandelt HTML5-Syntax, VSCode-Setup, das CSS-Boxmodell und mehr.`,

    snowAnimationTitle: "Schneeanimation <span>&#8599;</span>",
    snowDesc: `Eine einfache Website zur Visualisierung eines <b class="snowButton">Schneeeffekts</b> mit JavaScript. Deutsch inspiriert, mit Fokus auf ein einfaches, benutzerfreundliches UI.`,

    observerTitle: "Observer & Zähler <span>&#8599;</span>",
    observerDesc: `Observer- API mit JavaScript, die ein Zähleffekt beim Scrollen aktiviert.Verwendet in einem <a href="#" rel="noopener noreferrer" target="_blank">meiner</a> <a href="https://phlox.pro" rel="noopener noreferrer" target="_blank">Phlox-</a>inspirierten Projekte.`,

    pythonTitle: "Python <span>&#8599;</span>",
    pythonDesc: `Ich lerne Python zusammen mit Frameworks wie  <b class="tak">Django</b> und <b class="tak">Flask</b> zur Unterstützung der Webentwicklung.`,
    repoView: "Alle Repositories ansehen...",

    // Contact Section
    contactTitle: "KONTAKT",
    contactWa: `verfügbar für Website-bezongene projekte, Fragen oder Interessen; nur ein <b class="tak">dm</b> entfernt`,
    contactSlack: `Ich bin verfügbar, um zu Open-Source-frontend-Designs <b class="tak">beizutragen</b>. Ich mochte an aufregenden Webprojekten <b class="tak">zusammenarbeiten</b>.`,

    // Footer
    bChiang: `Website von  <a href="https://brittanychiang.com/" rel="noopener noreferrer" target="_blank">Brittany Chiang</a> inspiriert`,
    techStack: "Diese Website wurde mit HTML, CSS und JavaScript erstellt.",
    footer: "Alle Rechte vorbehalten",
  },
};

const switcher = document.getElementById("language-switcher");
const elements = {
  aboutNav: document.querySelector('a[href="#aboutPage"]'),
  projectsNav: document.querySelector('a[href="#projectsPage"]'),
  contactNav: document.querySelector('a[href="#contactPage"]'),
  jobTitle: document.querySelector(".info-text h3"),
  homeDesc: document.querySelector(".info-text p"),
  aboutTitle: document.querySelector(".about h1"),
  aboutP1: document.querySelector(".about .aboutP1"),
  aboutP2: document.querySelector(".about .aboutP2"),
  aboutP3: document.querySelector(".about .aboutP3"),
  aboutP4: document.querySelector(".about .aboutP4"),
  aboutP5: document.querySelector(".about .aboutP5"),
  projectsTitle: document.querySelector(".projects h1"),
  cssIntroTitle: document.querySelector(".projects .pro-card .cssIntroTitle"),
  cssDesc: document.querySelector(".projects .pro-card .cssDesc"),
  snowAnimationTitle: document.querySelector(
    ".projects .pro-card .snowAnimationTitle",
  ),
  snowDesc: document.querySelector(".projects .pro-card .snowDesc"),
  observerTitle: document.querySelector(".projects .pro-card .observerTitle"),
  observerDesc: document.querySelector(".projects .pro-card .observerDesc"),
  pythonTitle: document.querySelector(".projects .pro-card .pythonTitle"),
  pythonDesc: document.querySelector(".projects .pro-card .pythonDesc"),
  repoView: document.querySelector(".projects > p > a"),
  contactTitle: document.querySelector(".contact h1"),
  contactWa: document.querySelector(".contact .contactWa"),
  contactSlack: document.querySelector(".contact .contactSlack"),
  bChiang: document.querySelector("footer .bChiang"),
  techStack: document.querySelector("footer .techStack"),
  footer: document.querySelector("footer .footer"),
};

// 👇 Function to apply translations
function applyTranslations(lang) {
  const t = translations[lang];

  if (!t) return;

  elements.aboutNav.innerHTML = t.about;
  elements.projectsNav.innerHTML = t.projects;
  elements.contactNav.innerHTML = t.contact;
  elements.jobTitle.innerHTML = t.jobTitle;
  elements.homeDesc.innerHTML = t.homeDesc;
  elements.aboutTitle.innerHTML = t.aboutTitle;

  elements.aboutP1.innerHTML = t.aboutP1;
  elements.aboutP2.innerHTML = t.aboutP2;
  elements.aboutP3.innerHTML = t.aboutP3;
  elements.aboutP4.innerHTML = t.aboutP4;
  elements.aboutP5.innerHTML = t.aboutP5;

  elements.projectsTitle.innerHTML = t.projectsTitle;
  elements.cssIntroTitle.innerHTML = t.cssIntroTitle;
  elements.cssDesc.innerHTML = t.cssDesc;
  elements.snowAnimationTitle.innerHTML = t.snowAnimationTitle;
  elements.snowDesc.innerHTML = t.snowDesc;
  elements.observerTitle.innerHTML = t.observerTitle;
  elements.observerDesc.innerHTML = t.observerDesc;
  elements.pythonTitle.innerHTML = t.pythonTitle;
  elements.pythonDesc.innerHTML = t.pythonDesc;
  elements.repoView.innerHTML = t.repoView;
  elements.contactTitle.innerHTML = t.contactTitle;
  elements.contactWa.innerHTML = t.contactWa;
  elements.contactSlack.innerHTML = t.contactSlack;
  elements.bChiang.innerHTML = t.bChiang;
  elements.techStack.innerHTML = t.techStack;
  elements.footer.innerHTML = t.footer;
}

switcher.addEventListener("change", (e) => {
  const lang = e.target.value;
  localStorage.setItem("selectedLang", lang);

  if (lang === "de") {
    applyTranslations("de");
  } else {
    localStorage.removeItem("selectedLang");
    location.reload();
  }
});

// ✅ On page load
window.addEventListener("DOMContentLoaded", () => {
  const savedLang = localStorage.getItem("selectedLang");

  if (savedLang === "de") {
    switcher.value = "de";
    applyTranslations("de");
  }
});
