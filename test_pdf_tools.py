#!/usr/bin/env python3
"""
Script de teste rápido para ferramentas PDF

Execute com: python test_pdf_tools.py
"""

import sys
sys.path.insert(0, '/c/Users/Qb/AppData/Local/hermes/hermes-agent')

from tools.pdf_tool import (
    read_pdf, 
    pdf_metadata, 
    pdf_analyze,
    html_to_pdf_final,
    check_requirements
)
from pathlib import Path

def test_pdf_tools():
    print("=" * 60)
    print("🧪 TESTE DE FERRAMENTAS PDF")
    print("=" * 60)
    
    # Verificar requisitos
    print("\n1. Verificando requisitos...")
    req_ok = check_requirements()
    print(f"   PyMuPDF/pypdf disponíveis: {'✅' if req_ok else '❌'}")
    
    if not req_ok:
        print("   Instale: pip install pymupdf pypdf")
        return False
    
    # Verificar estrutura de pastas
    print("\n2. Verificando estrutura de pastas...")
    base = Path("/c/Users/Qb/Desktop/editrpdf")
    for folder in ["input", "output", "temp", "references"]:
        folder_path = base / folder
        exists = "✅" if folder_path.exists() else "❌"
        print(f"   {folder}/ {exists}")
    
    # Testar leitura de PDF (se houver arquivo)
    input_dir = base / "input"
    if input_dir.exists():
        pdf_files = list(input_dir.glob("*.pdf"))
        if pdf_files:
            print(f"\n3. Encontrados {len(pdf_files)} PDF(s) para processar")
            test_file = str(pdf_files[0])
            print(f"   Arquivo: {pdf_files[0].name}")
            
            # Testar leitura
            result = read_pdf(test_file)
            if result.get("success"):
                print(f"   ✅ Leitura OK - {result.get('pages', '?')} páginas")
                text = result.get("text", "")
                print(f"   Texto extraído: {len(text)} caracteres")
            else:
                print(f"   ❌ Erro na leitura: {result.get('error', 'desconhecido')}")
        else:
            print("\n3. Nenhum PDF em input/ - Aguardando arquivo")
    
    # Testar template HTML
    print("\n4. Verificando template HTML...")
    template_html = """
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <title>Template ABNT - Teste</title>
        <style>
            body { font-family: 'Times New Roman', serif; font-size: 12pt; line-height: 1.5; }
            .autor { text-align: right; font-style: italic; }
            h1 { font-size: 18pt; margin-top: 24pt; }
            fig { text-align: center; margin: 12pt 0; }
        </style>
    </head>
    <body>
        <div class="autor">Rodrigo Carlos Moraes</div>
        <h1>Título do Livro</h1>
        <p>Subtítulo do livro...</p>
        <fig><img src="fig_001.png" alt="Figura 1"></fig>
    </body>
    </html>
    """
    
    test_output = str(base / "temp" / "test_template.html")
    Path(test_output).parent.mkdir(parents=True, exist_ok=True)
    Path(test_output).write_text(template_html)
    print(f"   ✅ Template HTML criado: {test_output}")
    
    print("\n" + "=" * 60)
    print("✅ TESTE CONCLUÍDO")
    print("=" * 60)
    print("\nPróximos passos:")
    print("1. Coloque o PDF original em: input/")
    print("2. Execute: python test_pdf_tools.py")
    print("\nPara processar PDF:")
    print('   {Processar PDF: path="./input/seuarquivo.pdf", acoes=["ler_tudo", "extrair_fotos"]}')
    
    return True

if __name__ == "__main__":
    test_pdf_tools()