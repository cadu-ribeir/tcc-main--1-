const banner = document.querySelector('#banner');
const bannerInput = document.querySelector('#bannerInput');

const userImg = document.querySelector('#userImg');
const userInput = document.querySelector('#userImgInput');

openFile(banner, bannerInput);
openFile(userImg, userInput);

function openFile(div, input){
    div.addEventListener('click', function(){
        input.click();
    })
} 

function alterarImg(event, input) {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.querySelector('#'+input)
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}

const apresentacao = document.querySelector('#apresentacaoText');
apresentacao.style.height = "auto";
apresentacao.style.height = (apresentacao.scrollHeight)+'px';

apresentacao.addEventListener('input', () => {
    apresentacao.style.height = (apresentacao.scrollHeight)+'px';
})

const nome = document.querySelector('#userNameInput');

nome.addEventListener('input', () => {
    let newName = nome.value;
    const nomeApresentacao = document.querySelector('#nomeApresentacao');

    nomeApresentacao.textContent = newName; 
})

document.querySelector('#salvarBtn').addEventListener('click', () => {
    form = new FormData();

    const fimDeSemana = document.querySelector('#fimDeSemana');
    const abertura = document.querySelector('#horaAbertura');
    const fechamento = document.querySelector('#horaFechamento');
    const telContato = document.querySelector('#telContato');
    const banner = document.querySelector('#bannerInput');

    const selects = document.querySelectorAll('select');
    const inputs = document.querySelectorAll('input');
    const erroMsgm = document.querySelector('#errorMsgm');

    form.append('nome', nome.value);
    form.append('apresentacao', apresentacao.value);
    form.append('abertura', abertura.value);
    form.append('fechamento', fechamento.value);
    form.append('fimDeSemana', fimDeSemana.value);
    form.append('telContato', telContato.value);

    if(banner.files.length > 0){
        form.append('banner', banner.files[0]);
    }

    if(userInput.files.length > 0){
        form.append('fotoPerfil', userInput.files[0]);
    }
    
    fetch('editar.php', {
        method: 'POST',
        body: form
    })
    .then(response => response.json())
    .then(data => {
        if(data.status == 'sucesso'){
            const msgm = document.querySelector('#msgmAviso');
            msgm.classList.add('show');

            setTimeout(() => {
                msgm.classList.remove('show');
            }, 1500)

            verificaCampos();
            erroCampo(erroMsgm, '', 'none');
            inputs[3].style.borderColor = '#979393';

        }else if(data.status == 'variavelVazia'){
            verificaCampos();
            erroCampo(erroMsgm, 'Preencha todos os campos corretamente', 'block');

        }else if(data.status == 'erroTelefone'){
            inputs[3].style.borderColor = 'red';

            console.log(inputs);
            erroCampo(erroMsgm, 'Digite um telefone válido', 'block');
        }
    })
    .catch(error => console.log('erro', error));

    function erroCampo(erroMsgm, text, style){
        erroMsgm.style.display = style;
        erroMsgm.textContent = text;
    }   
    
    function verificaCampos(){
        let campos = [];
    
        selects.forEach(item => campos.push(item));
        inputs.forEach(item => campos.push(item));
        campos.push(apresentacao);
    
        campos.forEach(item => {
            if(item.value.trim() == ''){
                item.style.borderColor = 'red';
            }else{
                item.style.borderColor = '#979393';
            }
        })    
    }
})


