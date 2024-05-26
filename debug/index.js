const express = require('express');
const bodyParser = require('body-parser');

const app = express();
const port = 3003;

// Use body-parser middleware to parse JSON bodies
app.use(bodyParser.json());

// Log all incoming POST requests
app.post('/log', (req, res) => {
    console.log('📝', req.body);
    res.send('Logged successfully');
});

app.listen(port, () => {
    console.log(`Log server listening at http://localhost:${port}`);
});