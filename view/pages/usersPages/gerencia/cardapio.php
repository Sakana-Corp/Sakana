<div class="conteudo page-cardapio">

    <div class="cardapio-container">

        <center><h2>Categorias</h2></center>

        <!-- CATEGORIAS -->
        <div class="categorias">
            <div class="categoria ativa" data-categoria="categoria1">Categoria 1</div>
            <div class="categoria" data-categoria="categoria2">Categoria 2</div>
            <div class="categoria" data-categoria="categoria3">Categoria 3</div>
            <div class="categoria" data-categoria="categoria4">Categoria 4</div>
            <div class="categoria" data-categoria="categoria5">Categoria 5</div>
        </div>

        <!-- ABAS -->
        <div class="abas">
            <span class="aba ativa" data-aba="populares">Populares</span>
            <span class="aba" data-aba="recentes">Recentes</span>
        </div>

        <!-- PRODUTOS -->
        <div class="produtos" id="lista-produtos"></div>

    </div>

</div>

<script>
const dadosCardapio = {
    categoria1: {
        populares: [
            { nome: "Produto 1", preco: "R$00,00", imagem: "/Sakana/view/images/sushi.png" },
            { nome: "Produto 2", preco: "R$00,00", imagem: "/Sakana/view/images/sushi.png" }
        ],
        recentes: [
            { nome: "Produto 3", preco: "R$00,00", imagem: "/Sakana/view/images/sushi.png" }
        ]
    },
    categoria2: {
        populares: [
            { nome: "Produto A", preco: "R$00,00", imagem: "/Sakana/view/images/sushi.png" }
        ],
        recentes: [
            { nome: "Produto B", preco: "R$00,00", imagem: "/Sakana/view/images/sushi.png" }
        ]
    },
    categoria3: {
        populares: [
            { nome: "Item X", preco: "R$00,00", imagem: "/Sakana/view/images/sushi.png" }
        ],
        recentes: [
            { nome: "Item Y", preco: "R$00,00", imagem: "/Sakana/view/images/sushi.png" }
        ]
    },
    categoria4: {
        populares: [
            { nome: "Exemplo 1", preco: "R$00,00", imagem: "/Sakana/view/images/sushi.png" }
        ],
        recentes: [
            { nome: "Exemplo 2", preco: "R$00,00", imagem: "/Sakana/view/images/sushi.png" }
        ]
    },
    categoria5: {
        populares: [
            { nome: "Modelo 1", preco: "R$00,00", imagem: "/Sakana/view/images/sushi.png" }
        ],
        recentes: [
            { nome: "Modelo 2", preco: "R$00,00", imagem: "/Sakana/view/images/sushi.png" }
        ]
    }
};

let categoriaAtual = "categoria1";
let abaAtual = "populares";

const lista = document.getElementById("lista-produtos");
const categorias = document.querySelectorAll(".categoria");
const abas = document.querySelectorAll(".aba");

function renderizar() {
    const itens = dadosCardapio[categoriaAtual][abaAtual];
    lista.innerHTML = "";

    itens.forEach(item => {
        const div = document.createElement("div");
        div.classList.add("produto");

        div.innerHTML = `
            <img src="${item.imagem}">
            <h3>${item.nome}</h3>
            <span>${item.preco}</span>
        `;

        lista.appendChild(div);
    });

    const add = document.createElement("div");
    add.classList.add("add-btn");
    add.textContent = "+";
    lista.appendChild(add);
}

/* EVENTOS */
categorias.forEach(c => {
    c.addEventListener("click", () => {
        categorias.forEach(x => x.classList.remove("ativa"));
        c.classList.add("ativa");
        categoriaAtual = c.dataset.categoria;
        renderizar();
    });
});

abas.forEach(a => {
    a.addEventListener("click", () => {
        abas.forEach(x => x.classList.remove("ativa"));
        a.classList.add("ativa");
        abaAtual = a.dataset.aba;
        renderizar();
    });
});

renderizar();
</script>