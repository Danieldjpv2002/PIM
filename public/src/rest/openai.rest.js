const { OpenAIApi, Configuration } = require("openai");

const file = process.argv[2]; // Obtiene el primer argumento
const token = process.argv[3]; // Obtiene el tercer parámetro

const configuration = new Configuration({
    apiKey: token,
});

const openai = new OpenAIApi(configuration);

const resp = await openai.createTranscription(
    fs.createReadStream(`../storage/temp/${file}`),
    "whisper-1"
);

console.log(JSON.stringify(resp));