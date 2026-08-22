#!/usr/bin/env python3
"""
Ferramenta PDF para Hermes Agent.

Fornece funcionalidades de leitura, análise e criação de PDFs
baseadas nos Master Rules @CanalQb v9.0.

Uso:
    from tools.pdf_tool import read_pdf, pdf_to_html, html_to_pdf_final

    result = read_pdf("./input/documento.pdf")
    print(result["text"])
"""

import json
import logging
import os
import subprocess
import tempfile
from pathlib import Path
from typing import Dict, Any, Optional, List

from tools.registry import registry, tool_error

logger = logging.getLogger(__name__)


# ============================================================================
# LEITURA DE PDF
# ============================================================================

def check_requirements() -> bool:
    """Verifica se as dependências para PDF estão disponíveis."""
    try:
        import fitz  # PyMuPDF
        return True
    except ImportError:
        pass
    
    try:
        import pypdf
        return True
    except ImportError:
        pass
    
    # Fallback: usar pdftotext do poppler se disponível
    result = subprocess.run(
        ["which", "pdftotext"],
        capture_output=True,
        text=True
    )
    return result.returncode == 0


def read_pdf(path: str, page_range: Optional[str] = None) -> Dict[str, Any]:
    """
    Lê um PDF e extrai o texto.
    
    Args:
        path: Caminho para o arquivo PDF
        page_range: Opcional, intervalo de páginas "1-5" ou "all"
    
    Returns:
        Dicionário com 'text', 'pages', 'metadata'
    """
    path_obj = Path(path)
    if not path_obj.exists():
        return tool_error(f"Arquivo não encontrado: {path}")
    
    result = {"success": False, "text": "", "pages": 0, "metadata": {}}
    
    # Tentar PyMuPDF primeiro
    try:
        import fitz
        doc = fitz.open(str(path_obj))
        
        # Metadados
        metadata = doc.metadata or {}
        result["metadata"] = {
            "title": metadata.get("title", ""),
            "author": metadata.get("author", ""),
            "creator": metadata.get("creator", ""),
            "pages": len(doc),
        }
        
        # Extrair texto
        text_parts = []
        start_page = 0
        end_page = len(doc)
        
        if page_range and page_range != "all":
            try:
                parts = page_range.split("-")
                start_page = int(parts[0]) - 1
                end_page = int(parts[-1]) if len(parts) > 1 else start_page + 1
            except (ValueError, IndexError):
                start_page = 0
                end_page = len(doc)
        
        for i in range(max(0, start_page), min(end_page, len(doc))):
            page_text = doc[i].get_text()
            text_parts.append(f"--- Página {i+1} ---\n{page_text}")
        
        result["text"] = "\n\n".join(text_parts)
        result["pages"] = len(doc)
        result["success"] = True
        
        doc.close()
        return result
        
    except ImportError:
        pass
    except Exception as e:
        logger.warning(f"PyMuPDF falhou: {e}")
    
    # Fallback: pypdf
    try:
        from pypdf import PdfReader
        reader = PdfReader(str(path_obj))
        
        result["metadata"] = {
            "pages": len(reader.pages),
            "title": getattr(reader.metadata, "title", "") if reader.metadata else "",
            "author": getattr(reader.metadata, "author", "") if reader.metadata else "",
        }
        
        text_parts = []
        for i, page in enumerate(reader.pages):
            text_parts.append(f"--- Página {i+1} ---\n{page.extract_text()}")
        
        result["text"] = "\n\n".join(text_parts)
        result["pages"] = len(reader.pages)
        result["success"] = True
        return result
        
    except ImportError:
        pass
    except Exception as e:
        logger.warning(f"pypdf falhou: {e}")
    
    # Fallback: pdftotext (poppler)
    try:
        cmd = ["pdftotext", str(path_obj), "-"]
        if page_range and page_range != "all":
            parts = page_range.split("-")
            start = int(parts[0]) - 1
            end = int(parts[-1] if len(parts) > 1 else len(parts))
            cmd = ["pdftotext", "-f", str(start), "-l", str(end), str(path_obj), "-"]
        
        proc = subprocess.run(cmd, capture_output=True, text=True)
        if proc.returncode == 0:
            result["text"] = proc.stdout
            result["success"] = True
            
            # Contar páginas
            page_count = len([l for l in proc.stdout.split("\n") if l.startswith("--- Página")])
            result["pages"] = page_count if page_count > 0 else "unknown"
            
        return result
    except Exception as e:
        return tool_error(f"Falha ao ler PDF: {e}")


def pdf_metadata(path: str) -> Dict[str, Any]:
    """Obtém metadados do PDF."""
    result = read_pdf(path)
    if result.get("success"):
        return {"success": True, "metadata": result.get("metadata", {})}
    return result


def pdf_page_range(path: str, start: int, end: int) -> Dict[str, Any]:
    """Extrai páginas específicas de um PDF."""
    return read_pdf(path, page_range=f"{start}-{end}")


# ============================================================================
# CRIAÇÃO DE PDF (via HTML)
# ============================================================================

def html_to_pdf_final(html_content: str, output_path: str, options: Optional[Dict] = None) -> Dict[str, Any]:
    """
    Converte HTML para PDF usando Puppeteer/Chromium.
    
    Args:
        html_content: Conteúdo HTML completo
        output_path: Caminho do arquivo PDF de saída
        options: Opções adicionais (format, margin, etc.)
    
    Returns:
        Dicionário com 'success', 'path', 'size_kb'
    """
    if options is None:
        options = {}
    
    output_path = str(Path(output_path))
    Path(output_path).parent.mkdir(parents=True, exist_ok=True)
    
    # Criar script Python temporário para conversão
    python_script = '''
import json
import sys
from pathlib import Path

try:
    from reportlab.lib.pagesizes import A4
    from reportlab.pdfgen import canvas
    from reportlab.lib.styles import getSampleStyleSheet
    from reportlab.platypus import SimpleDocTemplate, Paragraph, Spacer
    from reportlab.lib.units import inch
    from reportlab.lib import colors
    from reportlab.lib.enums import TA_CENTER, TA_JUSTIFY
    
    # Ler HTML
    html_file = Path(r"{}")
    output_file = Path(r"{}")
    
    # Simple HTML to PDF conversion
    doc = SimpleDocTemplate(str(output_file), pagesize=A4)
    styles = getSampleStyleSheet()
    
    # Custom styles following @CanalQb rules
    styleH1 = styles['Heading1']
    styleH1.fontSize = 24
    styleH1.alignment = TA_CENTER
    styleH1 textColor = colors.darkblue
    
    story = []
    story.append(Paragraph("Document Generated by PDF Agent", styleH1))
    
    # Draw a separator
    story.append(Spacer(1, 12))
    
    # Save
    doc.build(story)
    
    print(json.dumps({{"success": True, "path": str(output_file)}}))
    
except Exception as e:
    print(json.dumps({{"success": False, "error": str(e)}}))
    sys.exit(1)
'''.format(html_content[:100], output_path)
    
    # Para conversão real, usar browser CDP ou wkhtmltopdf
    # Primeiro tentar wkhtmltopdf
    try:
        # Criar arquivo HTML temporário
        with tempfile.NamedTemporaryFile(mode='w', suffix='.html', delete=False) as f:
            f.write(html_content)
            temp_html = f.name
        
        # Tentar wkhtmltopdf
        cmd = ['wkhtmltopdf', '--quiet', temp_html, output_path]
        proc = subprocess.run(cmd, capture_output=True, text=True)
        
        Path(temp_html).unlink(missing_ok=True)
        
        if proc.returncode == 0 and Path(output_path).exists():
            size_kb = Path(output_path).stat().st_size / 1024
            return {
                "success": True,
                "path": output_path,
                "size_kb": round(size_kb, 2),
                "method": "wkhtmltopdf"
            }
    except FileNotFoundError:
        pass
    except Exception as e:
        logger.warning(f"wkhtmltopdf falhou: {e}")
    
    # Fallback: usar browser CDP via chromium
    try:
        output_dir = str(Path(output_path).parent)
        filename = Path(output_path).name
        
        # Usar browser_cdp se disponível
        cmd = '''
const fs = require('fs');
const puppeteer = require('puppeteer');

(async () => {
    const browser = await puppeteer.launch({headless: true});
    const page = await browser.newPage();
    
    await page.setContent(`''' + html_content.replace('`', '\\`').replace('\n', '\\n') + '''`);
    
    await page.pdf({
        path: "''' + output_path + '''",
        format: "A4",
        margin: {top: "20mm", right: "20mm", bottom: "20mm", left: "20mm"}
    });
    
    await browser.close();
    console.log(JSON.stringify({success: true, path: "''' + output_path + '''"}));
})();
'''
        
        # Salvar e executar
        script_path = Path(tempfile.gettempdir()) / "pdf_convert.js"
        with open(script_path, 'w') as f:
            f.write(cmd)
        
        proc = subprocess.run(
            ['node', str(script_path)],
            capture_output=True,
            text=True,
            cwd=output_dir
        )
        
        script_path.unlink(missing_ok=True)
        
        if Path(output_path).exists():
            size_kb = Path(output_path).stat().st_size / 1024
            return {
                "success": True,
                "path": output_path,
                "size_kb": round(size_kb, 2),
                "method": "puppeteer"
            }
    except Exception as e:
        logger.warning(f"Puppeteer fallback falhou: {e}")
    
    # Erro final
    return tool_error(
        "Nenhum método disponível para conversão HTML→PDF. "
        "Instale: pip install pymupdf pypdf ou apt install wkhtmltopdf"
    )


# ============================================================================
# ANÁLISE DE PDF
# ============================================================================

def pdf_analyze(path: str) -> Dict[str, Any]:
    """
    Análise completa de PDF para geração de conteúdo.
    
    Retorna resumo, palavras-chave, estrutura e sugestões.
    """
    result = read_pdf(path)
    if not result.get("success"):
        return result
    
    text = result.get("text", "")
    
    # Análise básica
    word_count = len(text.split())
    avg_word_len = sum(len(w) for w in text.split()) / max(1, word_count)
    
    # Palavras-chave (simples)
    words = text.lower().split()
    from collections import Counter
    word_freq = Counter(w for w in words if len(w) > 3 and w.isalpha())
    keywords = [w for w, c in word_freq.most_common(20)]
    
    # Estrutura (buscar headers)
    import re
    headers = re.findall(r'^#{1,6}\s+(.+)$', text, re.MULTILINE)
    
    return {
        "success": True,
        "analysis": {
            "word_count": word_count,
            "avg_word_length": round(avg_word_len, 2),
            "keywords": keywords[:10],
            "headers_found": len(headers),
            "header_titles": headers[:5] if headers else [],
            "recommendation": "Excelente para conteúdo técnico" if word_count > 1000 else "Precisa de mais detalhes"
        },
        "text_preview": text[:500] + "..." if len(text) > 500 else text
    }


def pdf_compare(pdf1_path: str, pdf2_path: str) -> Dict[str, Any]:
    """
    Compara dois PDFs e identifica diferenças.
    """
    result1 = read_pdf(pdf1_path)
    result2 = read_pdf(pdf2_path)
    
    if not result1.get("success") or not result2.get("success"):
        return tool_error("Falha ao ler um ou mais PDFs")
    
    text1 = result1.get("text", "")
    text2 = result2.get("text", "")
    
    # Comparação simples
    lines1 = set(text1.split('\n'))
    lines2 = set(text2.split('\n'))
    
    only_in_1 = lines1 - lines2
    only_in_2 = lines2 - lines1
    
    return {
        "success": True,
        "pdf1": Path(pdf1_path).name,
        "pdf2": Path(pdf2_path).name,
        "differences": {
            "lines_only_in_pdf1": len(only_in_1),
            "lines_only_in_pdf2": len(only_in_2),
            "common_lines": len(lines1 & lines2)
        }
    }


def pdf_merge(input_files: List[str], output_path: str) -> Dict[str, Any]:
    """
    Une múltiplos PDFs em um único arquivo.
    """
    try:
        from pypdf import PdfReader, PdfWriter
        
        writer = PdfWriter()
        
        for file_path in input_files:
            if not Path(file_path).exists():
                return tool_error(f"Arquivo não encontrado: {file_path}")
            
            reader = PdfReader(file_path)
            for page in reader.pages:
                writer.add_page(page)
        
        Path(output_path).parent.mkdir(parents=True, exist_ok=True)
        
        with open(output_path, 'wb') as f:
            writer.write(f)
        
        return {
            "success": True,
            "path": output_path,
            "pages_merged": len(writer.pages),
            "input_files": [Path(f).name for f in input_files]
        }
        
    except ImportError:
        return tool_error("pypdf não instalado. Instale com: pip install pypdf")
    except Exception as e:
        return tool_error(f"Falha ao unir PDFs: {e}")


def pdf_split(path: str, ranges: List[str]) -> Dict[str, Any]:
    """
    Divide um PDF em partes baseado em ranges.
    
    ranges: lista de strings como ["1-5", "6-10", "11-15"]
    """
    try:
        from pypdf import PdfReader, PdfWriter
        
        reader = PdfReader(path)
        output_dir = Path(path).parent
        base_name = Path(path).stem
        
        results = []
        for r in ranges:
            try:
                parts = r.split('-')
                start = int(parts[0]) - 1
                end = int(parts[-1])
                
                writer = PdfWriter()
                for i in range(start, min(end, len(reader.pages))):
                    writer.add_page(reader.pages[i])
                
                output_file = output_dir / f"{base_name}_p{start+1}-{end}.pdf"
                with open(output_file, 'wb') as f:
                    writer.write(f)
                
                results.append({
                    "success": True,
                    "output": str(output_file),
                    "pages": end - start
                })
            except Exception as e:
                results.append({"success": False, "error": str(e), "range": r})
        
        return {"success": True, "splits": results}
        
    except ImportError:
        return tool_error("pypdf não instalado. Instale com: pip install pypdf")
    except Exception as e:
        return tool_error(f"Falha ao dividir PDF: {e}")


# ============================================================================
# REGISTRO DE FERRAMENTAS
# ============================================================================

def _check_pdf_requirements() -> bool:
    """Verifica se dependências PDF estão disponíveis."""
    return check_requirements()


# Registro automático das ferramentas
registry.register(
    name="read_pdf",
    toolset="pdf",
    schema={
        "name": "read_pdf",
        "description": "Lê um PDF e extrai o texto. Fornece texto extraído, número de páginas e metadados.",
        "parameters": {
            "type": "object",
            "properties": {
                "path": {"type": "string", "description": "Caminho para o arquivo PDF"},
                "page_range": {"type": "string", "description": "Intervalo de páginas (ex: '1-5' ou 'all')", "default": "all"}
            },
            "required": ["path"]
        }
    },
    handler=lambda args, **kw: read_pdf(args.get("path"), args.get("page_range")),
    check_fn=_check_pdf_requirements
)

registry.register(
    name="pdf_metadata",
    toolset="pdf",
    schema={
        "name": "pdf_metadata",
        "description": "Obtém metadados de um PDF (título, autor, páginas)",
        "parameters": {
            "type": "object",
            "properties": {
                "path": {"type": "string", "description": "Caminho para o arquivo PDF"}
            },
            "required": ["path"]
        }
    },
    handler=lambda args, **kw: pdf_metadata(args.get("path")),
    check_fn=_check_pdf_requirements
)

registry.register(
    name="pdf_analyze",
    toolset="pdf",
    schema={
        "name": "pdf_analyze",
        "description": "Análise completa de PDF: palavras-chave, estrutura, resumo",
        "parameters": {
            "type": "object",
            "properties": {
                "path": {"type": "string", "description": "Caminho para o arquivo PDF"}
            },
            "required": ["path"]
        }
    },
    handler=lambda args, **kw: pdf_analyze(args.get("path")),
    check_fn=_check_pdf_requirements
)

registry.register(
    name="html_to_pdf_final",
    toolset="pdf",
    schema={
        "name": "html_to_pdf_final",
        "description": "Converte HTML/CSS para PDF final. Gera PDF de alta qualidade.",
        "parameters": {
            "type": "object",
            "properties": {
                "html_content": {"type": "string", "description": "Conteúdo HTML completo"},
                "output_path": {"type": "string", "description": "Caminho do PDF de saída"},
                "options": {"type": "object", "description": "Opções de conversão", "default": {}}
            },
            "required": ["html_content", "output_path"]
        }
    },
    handler=lambda args, **kw: html_to_pdf_final(args.get("html_content"), args.get("output_path"), args.get("options")),
    check_fn=_check_pdf_requirements
)

registry.register(
    name="pdf_merge",
    toolset="pdf",
    schema={
        "name": "pdf_merge",
        "description": "Une múltiplos PDFs em um único arquivo",
        "parameters": {
            "type": "object",
            "properties": {
                "input_files": {"type": "array", "items": {"type": "string"}, "description": "Lista de arquivos PDF de entrada"},
                "output_path": {"type": "string", "description": "Caminho do PDF de saída"}
            },
            "required": ["input_files", "output_path"]
        }
    },
    handler=lambda args, **kw: pdf_merge(args.get("input_files"), args.get("output_path")),
    check_fn=_check_pdf_requirements
)

registry.register(
    name="pdf_split",
    toolset="pdf",
    schema={
        "name": "pdf_split",
        "description": "Divide um PDF em partes específicas",
        "parameters": {
            "type": "object",
            "properties": {
                "path": {"type": "string", "description": "Caminho do PDF a ser dividido"},
                "ranges": {"type": "array", "items": {"type": "string"}, "description": "Lista de ranges (ex: ['1-5', '6-10'])"}
            },
            "required": ["path", "ranges"]
        }
    },
    handler=lambda args, **kw: pdf_split(args.get("path"), args.get("ranges")),
    check_fn=_check_pdf_requirements
)

registry.register(
    name="pdf_compare",
    toolset="pdf",
    schema={
        "name": "pdf_compare",
        "description": "Compara dois PDFs e identifica diferenças",
        "parameters": {
            "type": "object",
            "properties": {
                "pdf1_path": {"type": "string", "description": "Primeiro PDF"},
                "pdf2_path": {"type": "string", "description": "Segundo PDF"}
            },
            "required": ["pdf1_path", "pdf2_path"]
        }
    },
    handler=lambda args, **kw: pdf_compare(args.get("pdf1_path"), args.get("pdf2_path")),
    check_fn=_check_pdf_requirements
)