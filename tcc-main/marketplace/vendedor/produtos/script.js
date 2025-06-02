function alterarTamanhos(){
    const categoria = document.querySelector('#categoria').value;
    const tamanhoContainer = document.querySelector('#tamanhos-container');
    tamanhoContainer.innerHTML = '';

    let tamanhos = [];

    // }else if(categoria == 'calcados'){
    // }else if(categoria == 'oculos'){
    //     tamanhos = ['Pequeno', 'Médio', 'Grande'];
    // }else if(categoria == 'roupas intimas'){
    //     tamanhos = ['P', 'M', 'G', 'GG', 'XG'];
    // }else if(categoria == 'roupas infantis'){
    //     tamanhos = ['P', 'M', 'G', 'GG'];
    // }

    switch(categoria){
        case 'camisetas':
        case 'shorts': 
        case 'meias':  
        case 'roupas_intimas':
            tamanhos = ['PP', 'P', 'M', 'G', 'GG', 'XG', 'XXG'];
            break;
        case 'calcas':
            tamanhos = [36, 38, 40, 42, 44, 46, 48, 50, 52, 54];
            break;
        case 'calcados':
            tamanhos = ['33', '34', '35', '36', '37', '38', '39', '40', '41', '42', '43', '44'];
            break;
        case 'acessorios':
            tamanhos = ['Pequeno', 'médio', 'Grande', 'muito Grande'];
            break;
        default: return;
    }

    tamanhos.forEach(tamanho => {
        const div = document.createElement('div');
        div.classList.add('opcao');
        div.innerHTML = `<input type="checkbox" name="tamanhos" value="${tamanho}">${tamanho}`;

        tamanhoContainer.appendChild(div);
    });
}

document.querySelector('#categoria').addEventListener('change', alterarTamanhos);

window.onload = alterarTamanhos;

document.querySelector('#tamanhos-container').addEventListener('click', function(event){
    const item = event.target.closest('.opcao');
    if(item){
        item.classList.toggle('selected');
        item.querySelector('input').checked = !item.querySelector('input').checked;
    }
})

const opcoes = document.querySelectorAll('.opcao');

opcoes.forEach(item => {
    item.addEventListener('click', function() {
        this.classList.toggle('selected');
        this.querySelector('input').checked = !this.querySelector('input').checked;
    });
});

function previewImage(event, previewId) {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById(previewId);
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}

if(document.querySelector('#formulario')){
    document.querySelector('#formulario').addEventListener('submit', function(event){
        event.preventDefault();
    
        const colorsInput = document.querySelectorAll('input[name="cores"]:checked');
        const tamanhosInput = document.querySelectorAll('input[name="tamanhos"]:checked');
    
        let cores = [];
        let tamanhos = [];
    
        colorsInput.forEach(cor =>{
            cores.push(cor.value);
        })
    
        tamanhosInput.forEach(tamanho => {
            tamanhos.push(tamanho.value);
        })
    
        const form = new FormData(this);
    
        form.append('coresDisponiveis', cores);
        form.append('tamanhosDisponiveis', tamanhos);
    
        const erroMsg = document.querySelector('#erroMsg');
        const inputs = document.querySelectorAll('.avisoErro');
        const imagensBoxs = document.querySelectorAll('.imgAviso[src="imgPadrao.png"]');
    
        fetch('./cadastrar.php', {
            method: 'POST',
            body: form  
        })
        .then(response => response.json())
        .then(data => {
            if(data.status === 'erroVazil'){
                erroInputs('Preencha todos os campos');
            }else if(data.status === 'erroImagem'){
                erroInputs('Algo de errado com as imagens');
            }else if(data.status === 'imgemGrande'){
                erroInputs('Imagens muito pesadas');
            }else if(data.status === 'erroExtencao'){
                erroInputs('Não aceitamos este tipo de arquivo');
            }else if(data.status === 'erroMovimentacaoArquivo'){
                erroInputs('Erro ao mover as imagens');
            }else if(data.status === 'erroCadastro'){
                erroInputs('Erro ao cadastrar o produto');
            }else if(data.status === 'cadastrado'){
                document.querySelector('#cadastroAviso').classList.remove('hidden');
                document.querySelector('#cadastroAviso').classList.add('show');

                const url = document.querySelector('#url');

                setTimeout(() => {
                    window.location.href=`../index.php?nome=${url.value}`;
                }, 300)
            }
        })
        .catch(error => console.log('erro', error))
    
        function erroInputs(erroText){
            inputs.forEach((input, i) => {
                if(input.value == ''){
                    input.style.border = "1px solid red";
                }
                if(imagensBoxs[i]){
                    imagensBoxs[i].style.border = "1px solid red";
                } 
            })
    
            erroMsg.style.display = 'block';
            erroMsg.textContent = erroText;
        }
    })
}

