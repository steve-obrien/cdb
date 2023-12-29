To create a user experience in your Vue.js app similar to the ChatGPT app, where formatted code is displayed, you can use a combination of Markdown rendering and syntax highlighting libraries. Here’s a step-by-step guide to achieve this:

### 1. Install Required Libraries

First, install `marked` for Markdown rendering and a syntax highlighting library like `highlight.js`.

```bash
npm install marked highlight.js
```

### 2. Set Up Markdown Rendering with Code Highlighting

In your Vue component, you'll use `marked` alongside `highlight.js` to render Markdown and highlight code blocks. 

```javascript
<template>
  <div v-html="formattedResponse" class="chat-output"></div>
</template>

<script>
import marked from 'marked';
import hljs from 'highlight.js';
import 'highlight.js/styles/github.css'; // Or any other style you prefer

export default {
  data() {
    return {
      response: `Here is some code:

\`\`\`javascript
function helloWorld() {
  console.log('Hello, world!');
}
\`\`\`
      `,
    };
  },
  computed: {
    formattedResponse() {
      // Set options for marked
      marked.setOptions({
        highlight: function (code, lang) {
          const language = hljs.getLanguage(lang) ? lang : 'plaintext';
          return hljs.highlight(code, { language }).value;
        },
      });

      return marked(this.response);
    },
  },
};
</script>

<style>
/* Additional styles for your chat output */
.chat-output {
  /* Styles here */
}
</style>
```

In this setup:
- The `highlight` function within `marked.setOptions` is used to apply syntax highlighting to each code block found in the Markdown.
- `highlight.js/styles/github.css` is an example style for code highlighting. You can choose different styles provided by `highlight.js`.
- The `response` data property is where the ChatGPT response or any Markdown content goes. It includes a code block as an example.

### 3. Styling the Chat Output

You can add additional CSS styles to `.chat-output` to style the chat interface according to your preference.

### 4. Handle User Input and API Responses

In a real application, you would replace the static `response` data property with dynamic content, typically coming from user input and API responses (like those from ChatGPT).

### 5. Security Considerations

When using `v-html` to render HTML, ensure that the content is sanitized to prevent XSS attacks. Since you are rendering Markdown and handling code formatting, the risk is generally lower, but it's always good to be cautious with user-generated content.

This setup will give you a ChatGPT-like experience in your Vue.js application, where responses, including formatted code blocks, are displayed neatly and interactively.